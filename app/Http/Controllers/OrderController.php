<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Hold;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'hold_id' => 'required|integer|exists:holds,id',
            'user_id' => 'nullable|integer',
        ]);

        return DB::transaction(function () use ($data) {
            $hold = Hold::where('id', $data['hold_id'])->lockForUpdate()->first();

            if (!$hold) {
                throw ValidationException::withMessages(['hold_id' => ['Hold not found.']]);
            }

            if ($hold->status !== 'active') {
                throw ValidationException::withMessages(['hold_id' => ['Hold is not active.']]);
            }

            $product = Product::where('id', $hold->product_id)->lockForUpdate()->first();

            $order = Order::create([
                'hold_id' => $hold->id,
                'status' => 'pending',
                'quantity' => $hold->quantity,
                'unit_price' => $product->price,
                'total_amount' => (float) $product->price * (int) $hold->quantity,
                'user_id' => $data['user_id'] ?? null,
            ]);

            $hold->status = 'used';
            $hold->save();

            return response()->json([
                'order_id' => $order->id,
                'status' => $order->status,
            ], 201);
        });
    }
}
