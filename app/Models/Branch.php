<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'location',
        'manager_id',
    ];

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function birds()
    {
        return $this->hasMany(Bird::class);
    }

    public function eggCollections()
    {
        return $this->hasMany(EggCollection::class);
    }

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

    public function chickPurchases()
    {
        return $this->hasMany(ChickPurchase::class);
    }

    public function feedingLogs()
    {
        return $this->hasMany(FeedingLog::class);
    }

    public function healthChecks()
    {
        return $this->hasMany(HealthCheck::class);
    }
}
