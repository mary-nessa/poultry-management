<?php

namespace App\Models;

use App\Traits\HasUUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EggCollection extends Model
{
    use HasFactory, HasUUID;

    protected $fillable = [
        'total_eggs',
        'good_eggs',
        'damaged_eggs',
        'broken_eggs',
        'full_trays',
        '1_2_trays',
        'single_eggs',
        'collected_by',
        'branch_id',
        'collection_date',
    ];

    protected $casts = [
        'total_eggs' => 'integer',
        'good_eggs' => 'integer',
        'damaged_eggs' => 'integer',
        'broken_eggs' => 'integer',
        'full_trays' => 'integer',
        '1_2_trays' => 'integer',
        'single_eggs' => 'integer',
        'collection_date' => 'datetime',
    ];

    public function collectedBy()
    {
        return $this->belongsTo(User::class, 'collected_by');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}