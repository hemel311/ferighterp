<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\ContainerUpload;
class UsPackingList extends Model
{
    protected $guarded = [];

    public function container()
    {
        return $this->belongsTo(
            ContainerUpload::class,
            'container_upload_id'
        );
    }

    public function products()
    {
        return $this->hasMany(
            UsPackingListProduct::class,
            'us_packing_list_id'
        );
    }
}