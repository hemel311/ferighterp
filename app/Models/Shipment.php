<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shipment extends Model
{
    protected $fillable = [
        'booking_number',
        'shipment_type',
        'carrier',
        'vessel_name',
        'voyage',
        'port_of_loading',
        'port_of_discharge',
        'etd',
        'eta',
        'si_cut_off',
        'cy_cut_off',
        'container_qty',
        'remarks',
        'status'
    ];

    public function items()
    {
        return $this->hasMany(ShipmentItem::class);
    }
}
