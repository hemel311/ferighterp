<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShipmentItem extends Model
{
    protected $fillable = [
        'shipment_id',
        'hs_code',
        'item_name',
        'product_id'
    ];

    public function shipment()
    {
        return $this->belongsTo(Shipment::class);
    }
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
