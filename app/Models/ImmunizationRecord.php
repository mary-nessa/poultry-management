<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImmunizationRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'chick_purchased_id',
        'vaccine_id',
        'immunization_date',
        'next_due_date',
        'notes',
        'number_immunized',
        'age_category',
    ];

    public function bird()
    {
        return $this->belongsTo(Bird::class);
    }
}
