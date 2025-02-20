<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SaleItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'sale_id',
        'product_id',
        'quantity',
        'unit_price',
        'total_amount'
    ];

    public function sale() : BelongsTo
    {
        return $this->belongsTo(Sale::class);
    }

    // Each sale item optionally belongs to a product.
    public function product() : BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
