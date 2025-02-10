<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BirdTransfer extends Model
{
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'bird_id', 'from_branch_id', 'to_branch_id', 'quantity', 'transfer_date', 'notes',
    ];

    public function bird(): BelongsTo
    {
        return $this->belongsTo(Bird::class);
    }

    public function fromBranch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'from_branch_id');
    }

    public function toBranch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'to_branch_id');
    }
}
