<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UmkmSpecialty extends Model
{
    protected $fillable = ['umkm_id', 'name'];

    public function umkm()
    {
        return $this->belongsTo(Umkm::class);
    }
}
