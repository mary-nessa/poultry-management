<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasUUID;

class FeedType extends Model
{
    use HasUUID;

    public $incrementing = false;
    protected $keyType = 'string';
    
    protected $fillable = ['name', 'description'];
    
    public function feeds()
    {
        return $this->hasMany(Feed::class);
    }
}