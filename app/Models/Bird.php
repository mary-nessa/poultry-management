<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
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

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function expenses(): HasMany
    {
        return $this->hasMany(Expense::class);
    }

    public function chickPurchase()
    {
        return $this->belongsTo(ChickPurchase::class);
    }

}
