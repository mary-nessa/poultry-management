<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImmunizationRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'chick_purchase_id',
        'vaccine_id',
        'immunization_date',
        'next_due_date',
        'notes',
        'number_immunized',
        'age_category',
    ];

    /**
     * Relationship with ChickPurchase
     */
    public function chickPurchase()
    {
        return $this->belongsTo(ChickPurchase::class, 'chick_purchase_id');
    }

    /**
     * Relationship with Medicine (as a vaccine)
     */
    public function vaccine()
    {
        return $this->belongsTo(Medicine::class, 'vaccine_id');
    }


}
