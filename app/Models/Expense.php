<?php

namespace App\Models;

use App\Traits\HasUUID;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Expense extends Model
{
    use HasUUID;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'category',
        'amount',
        'description',
        'expense_date',
        'expense_type',
        'chick_purchase_id',
        'feed_id',
        'medicine_id',
        'equipment_id',
        'branch_id'
    ];

    protected $casts = [
        'expense_date' => 'datetime',
        'amount' => 'float'
    ];

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function chickPurchase(): BelongsTo
    {
        return $this->belongsTo(ChickPurchase::class);
    }

    public function feed(): BelongsTo
    {
        return $this->belongsTo(Feed::class);
    }

    public function medicine(): BelongsTo
    {
        return $this->belongsTo(Medicine::class);
    }

    public function equipment(): BelongsTo
    {
        return $this->belongsTo(Equipment::class);
    }
}
