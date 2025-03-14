<?php

namespace App\Models;

use App\Traits\HasUUID;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Supplier extends Model
{
    use HasUUID;
    
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'name', 
        'phone_country_code',
        'phone_number',
        'email',
        'branch_id',
    ];

    public function getContactInfoAttribute()
    {
        return $this->phone_country_code . $this->phone_number;
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function feeds(): HasMany
    {
        return $this->hasMany(Feed::class);
    }

    public function medicines(): HasMany
    {
        return $this->hasMany(Medicine::class);
    }

    public function equipments(): HasMany
    {
        return $this->hasMany(Equipment::class);
    }

    public function chickPurchases(): HasMany
    {
        return $this->hasMany(ChickPurchase::class);
    }
}