<?php

namespace App\Models;

use App\Traits\HasUUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ChickPurchase extends Model
{
    use HasFactory, HasUUID;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'batch_id',
        'breed_id',
        'purchase_age',
        'purchase_date',
        'branch_id',
        'quantity',
        'unit_cost',
        'total_cost',
        'supplier_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'purchase_date' => 'date',
    ];

    /**
     * Get the branch that this purchase belongs to.
     *
     * @return BelongsTo
     */
    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    /**
     * Get the supplier for this purchase.
     *
     * @return BelongsTo
     */
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    /**
     * Get the breed of the chicks in this purchase.
     *
     * @return BelongsTo
     */
    public function breed(): BelongsTo
    {
        return $this->belongsTo(Breed::class);
    }

    /**
     * Get the birds resulting from this purchase.
     *
     * @return HasMany
     */
    public function birds(): HasMany
    {
        return $this->hasMany(Bird::class, 'chick_purchase_id');
    }

    /**
     * Get the immunization records for this purchase.
     *
     * @return HasMany
     */
    public function immunizationRecords(): HasMany
    {
        return $this->hasMany(ImmunizationRecord::class);
    }
}