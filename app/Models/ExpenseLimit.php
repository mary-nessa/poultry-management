<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExpenseLimit extends Model
{
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'branch_id', 'category', 'limit_amount',
    ];

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }
}
