<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\HasUUID;

class GeneralInventory extends Model
{

    use HasFactory, HasUUID;

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
