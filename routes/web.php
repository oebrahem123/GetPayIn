<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HoldController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentWebhookController;
use App\Http\Controllers\AdminController;

Route::get('/admin', [AdminController::class, 'index']);
Route::get('/admin/products', [AdminController::class, 'products']);
Route::get('/admin/holds', [AdminController::class, 'holds']);
Route::get('/admin/orders', [AdminController::class, 'orders']);
Route::get('/admin/idempotency', [AdminController::class, 'idempotency']);





Route::post('/holds', [HoldController::class, 'store']);
Route::get('/orders', function () {
    return response('orders endpoint — use POST /api/orders via Postman', 200);
});
Route::post('/payments/webhook', [PaymentWebhookController::class, 'handle']);
