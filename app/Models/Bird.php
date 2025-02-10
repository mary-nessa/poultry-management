<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Bird extends Model
{
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'type', 'hen_count', 'cock_count', 'chick_count',
        'egg_laid_date', 'hatch_date', 'mortality_rate',
        'purchase_cost', 'acquisition_date', 'branch_id',
    ];

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function expenses(): HasMany
    {
        return $this->hasMany(Expense::class);
    }

    public function immunisations(): HasMany
    {
        return $this->hasMany(BirdImmunisation::class);
    }
}
