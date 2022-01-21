<?php

namespace App\Models\Location;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhilippineRegion extends Model
{
    use HasFactory;

    public function provinces()
    {
        return $this->hasMany(PhilippineProvince::class,'region_code');
    }
}
