<?php

namespace App\Models;

    use Illuminate\Database\Eloquent\Model;

class Isf extends Model
{
    protected $fillable = [
            'shipment_id',
            'mbl_prefix_id',
            'booking_number',
            'from_address',
            'to_address',
            'manufacturer',

            'product_name',
            'hs_code',

            'hbl',
            'mbl',

            'etd',

            'port_of_loading',
            'port_of_discharge',

            'container_numbers',
            'vessel_name',
            'voyage',

            'status',

    ];

    public function shipment()
    {
        return $this->belongsTo(Shipment::class);
    }

    public function prefix()
    {
        return $this->belongsTo(MblPrefix::class, 'mbl_prefix_id');
    }


}
