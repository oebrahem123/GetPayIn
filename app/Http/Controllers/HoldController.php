<?php

namespace App\Http\Controllers;

use App\Models\Hold;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class HoldController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'product_id' => 'required|integer|exists:products,id',
            'qty' => 'required|integer|min:1',
        ]);


        return DB::transaction(function () use ($data) {


            $product = Product::where('id', $data['product_id'])
                ->lockForUpdate()
                ->first();

            $available = $product->stock - $product->reserved;

            if ($data['qty'] > $available) {
                throw ValidationException::withMessages([
                    'qty' => ['Not enough stock.'],
                ]);
            }

            $product->reserved += $data['qty'];
            $product->save();

            $hold = Hold::create([
                'product_id' => $product->id,
                'quantity'   => $data['qty'],
                'status'     => 'active',
                'expires_at' => now()->addMinutes(2),
            ]);

            return response()->json([
                'hold_id'    => $hold->id,
                'expires_at' => $hold->expires_at->toDateTimeString(),
            ]);
        });
    }
}
