<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommercialInvoice extends Model
{
    protected $fillable = [
        'shipment_id',
        'calculation_sheet_id',
        'export_number',
        'shipping_cost'
    ];

    public function shipment()
    {
        return $this->belongsTo(
            Shipment::class
        );
    }

    public function calculation()
    {
        return $this->belongsTo(
            CalculationSheet::class,
            'calculation_sheet_id'
        );
    }
}
