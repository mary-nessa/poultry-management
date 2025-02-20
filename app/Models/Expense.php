<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'category',
        'amount',
        'description',
        'expense_date',
        'expense_type',
        'chick_purchase_id',
        'feed_id',
        'medicine_id',
        'equipment_id',
        'branch_id',
    ];

    public function chickPurchase()
    {
        return $this->belongsTo(ChickPurchase::class);
    }

    public function feed()
    {
        return $this->belongsTo(Feed::class);
    }

    public function medicine()
    {
        return $this->belongsTo(Medicine::class);
    }

    public function equipment()
    {
        return $this->belongsTo(Equipment::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}
