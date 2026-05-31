<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShipmentItem extends Model
{
    protected $fillable = [
        'shipment_id',
        'hs_code',
        'item_name',
    ];

    public function shipment()
    {
        return $this->belongsTo(Shipment::class);
    }
}
