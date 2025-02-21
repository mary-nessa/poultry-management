<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EggCollection extends Model
{
    use HasFactory;

    protected $fillable = [
        'total_eggs',//good_eggs + damaged_eggs
        'good_eggs',
        'damaged_eggs',
        'broken_eggs',
        'full_trays',
        '1_2_trays',
        'single_eggs',
        'collected_by',
        'branch_id',
    ];

    public function collectedBy()
    {
        return $this->belongsTo(User::class, 'collected_by');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }
}
