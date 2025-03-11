<?php

namespace App\Models;

use App\Traits\HasUUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ImmunizationRecord extends Model
{
    use HasFactory, HasUUID;

    protected $fillable = [
        'chick_purchase_id',
        'vaccine_id',
        'immunization_date',
        'next_due_date',
        'notes',
        'number_immunized',
        'age_category',
    ];

    protected $casts = [
        'immunization_date' => 'date',
        'next_due_date' => 'date',
        'number_immunized' => 'integer'
    ];

    public function chickPurchase(): BelongsTo
    {
        return $this->belongsTo(ChickPurchase::class, 'chick_purchase_id');
    }

    public function vaccine(): BelongsTo
    {
        return $this->belongsTo(Medicine::class, 'vaccine_id');
    }
}