<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\HasUUID;

class DailyActivity extends Model
{
    use HasFactory, HasUUID;

    protected $fillable = [
        'user_id',
        'branch_id',
        'activity_date',
        'feeding_notes',
        'health_notes',
        'egg_collection_count'
    ];

    protected $casts = [
        'activity_date' => 'date',
        'egg_collection_count' => 'integer'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function eggTray(): BelongsTo
    {
        return $this->belongsTo(EggTray::class);
    }
}
