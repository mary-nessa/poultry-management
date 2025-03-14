<?php

namespace App\Models;

use App\Traits\HasUUID;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Buyer extends Model
{
    use HasUUID;
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'name', 
        'phone_country_code',
        'phone_number',
        'email',
        'buyer_type',
    ];

    public function getContactInfoAttribute()
    {
        return $this->phone_country_code . $this->phone_number;
    }

    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class);
    }
}