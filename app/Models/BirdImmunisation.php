<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BirdImmunisation extends Model
{
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'bird_id', 'vaccine_name', 'immunisation_date', 'next_due_date',
        'notes', 'number_immunized', 'age_category',
    ];

    public function bird(): BelongsTo
    {
        return $this->belongsTo(Bird::class);
    }
}
