<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class BirdBatch extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'bird_batches';

    protected $fillable = [
        'branch_id',
        'purchase_method',
        'purchased_quantity',
        'unknown_gender',
        'hen_count',
        'cock_count',
        'egg_laid_date',
        'hatch_date',
        'actual_hatched',
        'purchase_price',
        'acquisition_date',
        'status',
        'supplier_id'
    ];

    protected $casts = [
        'egg_laid_date'      => 'date',
        'hatch_date'         => 'date',
        'acquisition_date'   => 'date',
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function birds()
    {
        return $this->hasMany(Bird::class);
    }
}
