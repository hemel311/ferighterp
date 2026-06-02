<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\UsPackingList;
use App\Models\Vgminfo;

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
    public function trPackingLists()
    {
        return $this->hasMany(
            TrPackingList::class,
            'container_upload_id'
        );
    }
    public function usPackingLists()
    {
        return $this->hasMany(
            UsPackingList::class,
            'container_upload_id'
        );
    }}
