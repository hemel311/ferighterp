<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UsPackingListProduct extends Model
{
    protected $guarded = [];

    public function packingList()
    {
        return $this->belongsTo(
            UsPackingList::class,
            'us_packing_list_id'
        );
    }
}