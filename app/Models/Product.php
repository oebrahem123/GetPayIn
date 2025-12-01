<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Hold;
class Product extends Model
{
    use HasFactory;
     protected $fillable = [
        'name',
        'price',
        'stock',
        'reserved',
    ];

    protected $casts = [
        'stock' => 'integer',
        'reserved' => 'integer',
        'price' => 'decimal:2',
    ];
    public function holds(): HasMany
    {
          return $this->hasMany(Hold::class);
    }
}

