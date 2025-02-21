<?php

namespace App\Models;

use App\Traits\HasUUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles, HasUUID;

    protected $fillable = [
        'name',
        'email',
        'password',
        'branch_id',
    ];

    // A user (if not an admin) belongs to a branch.
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    // A user who is a manager may manage one branch.
    public function managedBranch()
    {
        return $this->hasOne(Branch::class, 'manager_id');
    }

    // Relations to logs or collections (if the user is a worker)
    public function feedingLogs()
    {
        return $this->hasMany(FeedingLog::class, 'worker_id');
    }

    public function healthChecks()
    {
        return $this->hasMany(HealthCheck::class, 'worker_id');
    }

    public function eggCollections()
    {
        return $this->hasMany(EggCollection::class, 'collected_by_id');
    }
}
