<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AddressController extends Controller
{
    /**
     * @param $regionCode
     * @return \Illuminate\Support\Collection
     */
    public function getProvince($regionCode)
    {
        return DB::table('philippine_provinces')->where('region_code', $regionCode)->get();
    }

    /**
     * @param $provinceCode
     * @return \Illuminate\Support\Collection
     */
    public function getCities($provinceCode)
    {
        return DB::table('philippine_cities')->where('province_code',$provinceCode)->get();
    }

    public function getBarangays($cityCode)
    {
        return DB::table('philippine_barangays')->where('city_municipality_code',$cityCode)->get();
    }
}
