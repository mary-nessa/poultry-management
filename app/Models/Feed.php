<?php
namespace App\Models;

use App\Traits\HasUUID;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Feed extends Model
{
    use HasUUID;
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'feed_type_id', 'quantity_kg', 'purchase_date', 'unit_cost', 'total_cost', 'supplier_id',
    ];

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function expenses(): HasMany
    {
        return $this->hasMany(Expense::class);
    }

    public function feedType()
    {
        return $this->belongsTo(FeedType::class, 'feed_type_id'); // Specify the foreign key if it's not 'feed_type_id'
    }
}
