<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    protected $fillable = [
        'hold_id',
        'status',
        'quantity',
        'unit_price',
        'total_amount',
        'payment_provider_id',
        'user_id',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'unit_price' => 'decimal:2',
        'total_amount' => 'decimal:2',
    ];

    public function hold(): BelongsTo
    {
        return $this->belongsTo(Hold::class);
    }
}
