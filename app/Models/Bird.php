<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\HasUUID;

class Bird extends Model
{
    use HasFactory, HasUUID;

    protected $fillable = [
        'chick_purchase_id',
        'cock_count',
        'hen_count',
        'total_birds',
        'laying_cycle_start_date',
        'laying_cycle_end_date',
    ];

    public function branch(): HasOneThrough
    {
        return $this->hasOneThrough(
            Branch::class,
            ChickPurchase::class,
            'id', // Foreign key on chick_purchases table
            'id', // Foreign key on branches table
            'chick_purchase_id', // Local key on birds table
            'branch_id' // Local key on chick_purchases table
        );
    }

    public function expenses(): HasMany
    {
        return $this->hasMany(Expense::class);
    }

    public function chickPurchase(): BelongsTo
    {
        return $this->belongsTo(ChickPurchase::class);
    }
}
