<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EggTray extends Model
{
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'tray_type', 'total_eggs', 'damaged_eggs', 'collected_at', 'status', 'branch_id',
    ];

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function eggTransfers(): HasMany
    {
        return $this->hasMany(EggTransfer::class);
    }
}
