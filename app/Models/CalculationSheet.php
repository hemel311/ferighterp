<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CalculationSheet extends Model
{
protected $fillable = [
'shipment_id',
'tcmb',
'shipping_cost',
'percentage'
];

    public function shipment()
    {
        return $this->belongsTo(Shipment::class);
    }

    public function items()
    {
        return $this->hasMany(CalculationItem::class);
    }
}
