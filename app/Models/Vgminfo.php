<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vgminfo extends Model
{
    protected $fillable = [
        'container_id',
        'vgm_weight',
        'container_weight',
        'gross_weight',
    ];

    public function container()
    {
        return $this->belongsTo(ContainerUpload::class);
    }
    public function trPackingLists()
    {
        return $this->hasMany(
            TrPackingList::class,
            'vgm_info_id'
        );
    }
}
