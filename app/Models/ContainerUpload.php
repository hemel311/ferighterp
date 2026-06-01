<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContainerUpload extends Model
{
    protected $fillable = [
        'booking_number',
        'container_serial',
        'container_number',
        'seal_number',
        'container_image',
        'seal_image',
    ];
    public function vgmInfo()
    {
        return $this->hasOne(Vgminfo::class, 'container_id', 'id');
    }
}
