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
    public function containers()
    {
        return $this->hasMany(
            ContainerUpload::class,
            'booking_number',
            'booking_number'
        );
    }
    public function trPackingLists()
    {
        return $this->hasMany(TrPackingList::class);
    }
    public function calculationSheets()
    {
        return $this->hasMany(
            CalculationSheet::class
        );
    }
}
