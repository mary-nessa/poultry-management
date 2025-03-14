<?php

namespace App\Models;

use App\Traits\HasUUID;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sale extends Model
{
    use HasUUID;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'sale_date',
        'branch_id',
        'buyer_id',
        'payment_method',
        'is_paid',
        'balance'
    ];

    protected $appends = ['total_amount'];

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function buyer(): BelongsTo
    {
        return $this->belongsTo(Buyer::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(SaleItem::class);
    }

    public function getTotalAmountAttribute()
    {
        return $this->items->sum('total_amount');
    }

    public function scopeWithTotalAmount($query)
    {
        return $query->withSum('items', 'total_amount');
    }
}
