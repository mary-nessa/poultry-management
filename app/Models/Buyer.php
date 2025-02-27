<?php

namespace App\Models;

use App\Traits\HasUUID;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Buyer extends Model
{
    use HasUUID;
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'name', 'contact_info', 'buyer_type',
    ];


    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class);
    }
}
