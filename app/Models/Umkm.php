<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Umkm extends Model
{
    protected $fillable = [
        'name',
        'category',
        'description',
        'full_description',
        'image',
        'location',
        'address',
        'phone',
        'email',
        'owner',
        'operating_hours',
        'established',
        'employees'
    ];

    public function galleries()
    {
        return $this->hasMany(UmkmGallery::class);
    }

    public function achievements()
    {
        return $this->hasMany(UmkmAchievement::class);
    }

    public function specialties()
    {
        return $this->hasMany(UmkmSpecialty::class);
    }
}
