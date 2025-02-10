<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'product_type', 'default_price',
    ];

    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class);
    }
}
