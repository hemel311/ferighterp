<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CalculationItem extends Model
{
    protected $fillable = [
        'calculation_sheet_id',
        'turkish_name',
        'english_name',
        'container_quantities',
        'invoice_qty',
        'original_price',
        'item_price',
        'tl_usd',
        'shipping_additional',
        'cif_price',
        'tl_total',
    ];

    protected $casts = [
        'container_quantities' => 'array'
    ];

    public function calculationSheet()
    {
        return $this->belongsTo(
            CalculationSheet::class
        );
    }

    public function product()
    {
        return $this->belongsTo(
            Product::class
        );
    }
}
