<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DailyActivity extends Model
{
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'worker_id', 'branch_id', 'activity_date', 'feeding_notes', 'health_notes',
        'egg_collection_count', 'damaged_egg_count', 'egg_sales_count',
        'hen_sale_expenses', 'feed_consumed_kg', 'egg_tray_id',
    ];

    public function worker(): BelongsTo
    {
        return $this->belongsTo(User::class, 'worker_id');
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
