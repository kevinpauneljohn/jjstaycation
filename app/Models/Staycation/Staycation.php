<?php

namespace App\Models\Staycation;

use App\Models\Location\PhilippineBarangay;
use App\Models\Location\PhilippineCity;
use App\Models\Location\PhilippineProvince;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Staycation extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'owner_id',
        'name',
        'address',
        'description'
    ];

    protected $casts = [
        'address' => 'object'
    ];

    protected static function booted(){
        static::saving(function($staycation){
            $staycation->created_by = auth()->user()->id;
        });
    }

    public function getFullAddressAttribute()
    {
        $barangay = $this->address->barangay !== null ? 'Brgy. '.PhilippineBarangay::where('barangay_code',$this->address->barangay)->first()->barangay_description : "";
        $city = $this->address->city !== null ? PhilippineCity::where('city_municipality_code',$this->address->city)->first()->city_municipality_description.' City' : '';
        $province = $this->address->province !== null ? PhilippineProvince::where('province_code',$this->address->province)->first()->province_description : '';
//        return "{$this->address->address_number}";
        return $this->address->address_number.', '.$barangay.', '.$city.', '.$province;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function owner()
    {
        return $this->belongsTo(User::class,'owner_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class,'staycation_user');
    }

    public function packages()
    {
        return $this->hasMany(Package::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function customers()
    {
        return $this->belongsToMany(Customer::class,'bookings','staycation_id','customer_id');
    }
}
