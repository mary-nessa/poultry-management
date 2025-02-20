<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GeneralInventory extends Model
{
    protected $fillable = [
        'branch_id',
        'breed',
        'total_eggs',
        'total_chicks',
        'total_cocks',
        'total_hens',
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

}
