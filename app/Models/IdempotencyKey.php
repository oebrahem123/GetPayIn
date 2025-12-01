<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IdempotencyKey extends Model
{
    protected $fillable = [
        'key',
        'resource_type',
        'resource_id',
        'response_snapshot',
    ];

    protected $casts = [
        'response_snapshot' => 'array',
    ];
}
