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
    ];

    protected $casts = [
        'total_eggs' => 'integer',
        'good_eggs' => 'integer',
        'damaged_eggs' => 'integer',
        'broken_eggs' => 'integer',
        'full_trays' => 'integer',
        '1_2_trays' => 'integer',
        'single_eggs' => 'integer',
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

    // Calculate total eggs based on good and damaged eggs
    protected static function boot()
    {
        parent::boot();
        
        static::saving(function ($model) {
            $model->total_eggs = $model->good_eggs + $model->damaged_eggs;
        });
    }

    // Helper method to calculate eggs in trays (assuming 30 eggs per tray)
    public function calculateTrays()
    {
        $totalGoodEggs = $this->good_eggs;
        $this->full_trays = floor($totalGoodEggs / 30);
        $remaining = $totalGoodEggs % 30;
        $this->{'1_2_trays'} = floor($remaining / 15);
        $this->single_eggs = $remaining % 15;
        return $this;
    }

    // Get the monetary value of collected eggs (can be used for reporting)
    public function calculateValue($pricePerEgg)
    {
        return $this->good_eggs * $pricePerEgg;
    }
}
