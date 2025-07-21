<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UmkmAchievement extends Model
{
    protected $fillable = ['umkm_id', 'title'];

    public function umkm()
    {
        return $this->belongsTo(Umkm::class);
    }
}
