<?php

namespace App\Http\Controllers;

use App\Models\IdempotencyKey;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentWebhookController extends Controller
{
    /**
     * Handle payment webhook with HMAC verification.
     *
     * Expected JSON body:
     * {
     *   "idempotency_key": "string",
     *   "order_id": 1,
     *   "status": "success" | "failed",
     *   "provider_id": "optional-string"
     * }
     *
     * Signature header: X-Signature (hex lowercase sha256 HMAC over raw body using WEBHOOK_SECRET)
     */
    public function handle(Request $request)
    {
        // 1) Verify signature header existence
        $signature = $request->header('X-Signature') ?? $request->header('X_SIGNATURE') ?? null;
        $secret = env('WEBHOOK_SECRET');

        if (!$signature || !$secret) {
            Log::warning('Webhook: missing signature or secret.', [
                'has_signature' => (bool) $signature,
                'secret_present' => (bool) $secret,
            ]);
            return response()->json(['message' => 'Missing signature or server secret'], 400);
        }

        // 2) Compute HMAC on raw request body and compare (timing-safe)
        $rawBody = $request->getContent();
        $computed = hash_hmac('sha256', $rawBody, $secret);

        if (!hash_equals($computed, $signature)) {
            Log::warning('Webhook: invalid signature.', [
                'provided' => $signature,
                'computed' => $computed,
            ]);
            return response()->json(['message' => 'Invalid signature'], 401);
        }

        // 3) Validate payload (now that signature is verified)
        $data = $request->validate([
            'idempotency_key' => 'required|string',
            'order_id'        => 'required|integer|exists:orders,id',
            'status'          => 'required|string',
            'provider_id'     => 'nullable|string',
        ]);

        // 4) Idempotency: try to create a key row; duplicate = ignore
        try {
            $ikey = IdempotencyKey::create([
                'key' => $data['idempotency_key'],
                'resource_type' => 'payment:webhook',
                'resource_id' => $data['order_id'],
            ]);
        } catch (\Throwable $e) {
            // duplicate or db error -> ignore duplicate webhooks
            Log::info('Webhook: duplicate idempotency key ignored.', [
                'key' => $data['idempotency_key'],
                'order_id' => $data['order_id'],
            ]);
            return response()->json(['message' => 'duplicate webhook ignored'], 200);
        }

        // 5) Process according to status inside transactions with locks
        if ($data['status'] === 'success') {
            DB::transaction(function () use ($data, $ikey) {
                $order = Order::where('id', $data['order_id'])->lockForUpdate()->first();
                if (!$order) return;
                if ($order->status === 'paid') return;

                $hold = $order->hold()->lockForUpdate()->first();
                if (!$hold) return;

                $product = Product::where('id', $hold->product_id)->lockForUpdate()->first();
                if (!$product) return;

                $product->stock = max(0, $product->stock - $order->quantity);
                $product->reserved = max(0, $product->reserved - $order->quantity);
                $product->save();

                $order->status = 'paid';
                $order->payment_provider_id = $data['provider_id'] ?? null;
                $order->save();

                $ikey->response_snapshot = ['status' => 'processed', 'order_id' => $order->id];
                $ikey->save();
            });
        } else {
            DB::transaction(function () use ($data, $ikey) {
                $order = Order::where('id', $data['order_id'])->lockForUpdate()->first();
                if (!$order) return;
                if ($order->status === 'paid') return;

                $hold = $order->hold()->lockForUpdate()->first();
                $product = $hold ? Product::where('id', $hold->product_id)->lockForUpdate()->first() : null;

                if ($product) {
                    $product->reserved = max(0, $product->reserved - $order->quantity);
                    $product->save();
                }

                $order->status = 'cancelled';
                $order->save();

                $ikey->response_snapshot = ['status' => 'cancelled', 'order_id' => $order->id];
                $ikey->save();
            });
        }

        return response()->json(['message' => 'processed'], 200);
    }
}
