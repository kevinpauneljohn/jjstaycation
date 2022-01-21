<?php

namespace App\Models\Location;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhilippineCity extends Model
{
    use HasFactory;

    public function province()
    {
        return $this->belongsTo(PhilippineProvince::class,'province_code');
    }

    public function barangays()
    {
        return $this->hasMany(PhilippineBarangay::class,'city_municipality_code');
    }
}
