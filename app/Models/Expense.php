<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Expense extends Model
{
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'category', 'amount', 'description', 'expense_date', 'expense_type',
        'bird_id', 'feed_id', 'medicine_id', 'equipment_id', 'branch_id',
    ];

    public function bird(): BelongsTo
    {
        return $this->belongsTo(Bird::class);
    }

    public function feed(): BelongsTo
    {
        return $this->belongsTo(Feed::class);
    }

    public function medicine(): BelongsTo
    {
        return $this->belongsTo(Medicine::class);
    }

    public function equipment(): BelongsTo
    {
        return $this->belongsTo(Equipment::class);
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }
}
