<?php

namespace App\Models\Location;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhilippineProvince extends Model
{
    use HasFactory;

    public function region()
    {
        $this->belongsTo(PhilippineRegion::class,'region_code');
    }

    public function city()
    {
        return $this->hasMany(PhilippineCity::class,'province_code');
    }
}
