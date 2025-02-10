<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Sale extends Model
{
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'product_type', 'quantity', 'price_per_unit', 'total_amount', 'sale_date',
        'branch_id', 'buyer_id', 'egg_tray_id', 'product_id', 'payment_method', 'is_paid', 'balance',
    ];

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function buyer(): BelongsTo
    {
        return $this->belongsTo(Buyer::class);
    }

    public function eggTray(): BelongsTo
    {
        return $this->belongsTo(EggTray::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
