<?php

namespace App\Models\Location;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhilippineBarangay extends Model
{
    use HasFactory;

    public function city()
    {
        return $this->belongsTo(PhilippineCity::class,'city_municipality_code');
    }
}
