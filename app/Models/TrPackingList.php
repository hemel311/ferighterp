<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class TrPackingList extends Model
{
    protected $fillable = [
        'shipment_id',
        'container_upload_id',
        'vgm_info_id',
        'pl_date',
        'from_location',
        'to_location',
        'total_gross_weight',
        'total_net_weight',
        'total_pallets',
        'total_packages',
        'total_item_quantity',
        'status',
    ];

    public function shipment()
    {
        return $this->belongsTo(Shipment::class);
    }

    public function container()
    {
        return $this->belongsTo(ContainerUpload::class,'container_upload_id');
    }

    public function vgm()
    {
        return $this->belongsTo(Vgminfo::class,'vgm_info_id');
    }

    public function items()
    {
        return $this->hasMany(TrPackingListItem::class);
    }
}
