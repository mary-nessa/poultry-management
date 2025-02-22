<?php

namespace App\Models;

use App\Traits\HasUUID;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Supplier extends Model
{
    use HasUUID;
    
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'name', 'contact_info',
    ];

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
