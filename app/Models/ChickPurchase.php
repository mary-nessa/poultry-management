<?php

namespace App\Models;

use App\Traits\HasUUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChickPurchase extends Model
{
    use HasFactory, HasUUID;

    protected $fillable = [
        'batch_id',
        'breed',
        'purchase_age',
        'branch_id',
        'quantity',
        'unit_cost',
        'total_cost',
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
