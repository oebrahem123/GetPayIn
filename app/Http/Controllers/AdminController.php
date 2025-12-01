<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Hold;
use App\Models\Order;
use App\Models\IdempotencyKey;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $counts = [
            'products' => Product::count(),
            'holds'    => Hold::count(),
            'orders'   => Order::count(),
            'ikeys'    => IdempotencyKey::count(),
        ];

        $recentOrders = Order::latest()->limit(5)->get();
        return view('admin.index', compact('counts', 'recentOrders'));
    }

    public function products()
    {
        $products = Product::orderBy('id','desc')->paginate(15);
        return view('admin.products', compact('products'));
    }

    public function holds()
    {
        $holds = Hold::with('product')->orderBy('id','desc')->paginate(15);
        return view('admin.holds', compact('holds'));
    }

    public function orders()
    {
        $orders = Order::with('hold')->orderBy('id','desc')->paginate(15);
        return view('admin.orders', compact('orders'));
    }

    public function idempotency()
    {
        $ikeys = IdempotencyKey::orderBy('id','desc')->paginate(20);
        return view('admin.idempotency', compact('ikeys'));
    }
}
