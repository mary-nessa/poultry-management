<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChickPurchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'batch_id',
        'breed',
        'purchase_age',
        'branch_id',
        'quantity',
        'unit_cost',
        'total_cost',
        'date',
        'supplier_id',
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    // Link to the resulting poultry records.
    public function poultry()
    {
        return $this->hasMany(Bird::class, 'chick_purchase_id');
    }
}
