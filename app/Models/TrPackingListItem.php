<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrPackingListItem extends Model
{
    protected $fillable = [
        'tr_packing_list_id',
        'product_name',
        'description',
        'total_pallets',
        'total_packages',
        'quantity_per_unit',
        'item_quantity',
        'gross_weight',
        'net_weight',
    ];

    public function packingList()
    {
        return $this->belongsTo(
            TrPackingList::class,
            'tr_packing_list_id'
        );
    }
}
