<?php

namespace App\Models;
use App\Traits\HasUUID;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Medicine extends Model
{
    use HasUUID;
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'name', 'quantity', 'unit_cost', 'expiry_date', 'total_cost', 'supplier_id', 'purpose',
    ];

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function expenses(): HasMany
    {
        return $this->hasMany(Expense::class);
    }
}
