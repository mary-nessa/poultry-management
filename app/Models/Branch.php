<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\HasUUID;

class Branch extends Model
{
    use HasFactory, HasUUID;
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'name', 'location',
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function birds(): HasMany
    {
        return $this->hasMany(Bird::class);
    }

    public function eggTrays(): HasMany
    {
        return $this->hasMany(EggTray::class);
    }

    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class);
    }

    public function expenses(): HasMany
    {
        return $this->hasMany(Expense::class);
    }

    // Optionally, a helper to get the branch manager (assuming one manager per branch)
    public function manager(): HasOne
    {
        return $this->hasOne(Manager::class, 'branch_id');
    }

}
