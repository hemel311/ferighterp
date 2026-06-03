<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrPackingListItem extends Model
{
    protected $fillable = [
        'tr_packing_list_id',
        'product_name',
        'is_special_product',
        'quantity_per_unit',
        'total_pallets',
        'total_packages',
        'item_quantity',
        'net_weight',
        'gross_weight',
    ];

    public function packingList()
    {
        return $this->belongsTo(
            TrPackingList::class,
            'tr_packing_list_id'
        );
    }
}
