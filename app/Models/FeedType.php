<?php

// app/Models/FeedType.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeedType extends Model
{
    protected $fillable = ['name', 'description'];
    
    public function feeds()
    {
        return $this->hasMany(Feed::class);
    }
}