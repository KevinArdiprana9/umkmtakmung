<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UmkmGallery extends Model
{
    protected $fillable = ['umkm_id', 'image_url'];

    public function umkm()
    {
        return $this->belongsTo(Umkm::class);
    }
}
