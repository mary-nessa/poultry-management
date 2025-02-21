<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeedingLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'feed_consumed_kg',
        'feed_notes',
        'worker_id',
        'branch_id',
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
