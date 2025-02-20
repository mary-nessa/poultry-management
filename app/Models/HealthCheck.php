<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HealthCheck extends Model
{
    use HasFactory;

    protected $fillable = [
        'check_date',
        'health_notes',
        'worker_id',
        'branch_id',
        'breed',
        'alive_chicks',
        'dead_chicks',
        'alive_hens',
        'dead_hens',
        'sick_hens',
        'sick_chicks',
    ];

    public function worker()
    {
        return $this->belongsTo(User::class, 'worker_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}
