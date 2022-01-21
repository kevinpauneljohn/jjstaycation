<?php

namespace App\Models\Staycation;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'firstname',
        'middlename',
        'lastname',
        'email',
        'mobile_number',
        'proof_of_identity',
        'facebook_url',
        'companions',
        'created_by',
    ];

    protected static function booted(){
        static::saving(function($customer){
            $customer->created_by = auth()->user()->id;
        });
    }

    /**
     * get customer full name
     * @return mixed|string
     */
    public function getFullNameAttribute()
    {
        return "{$this->firstname} {$this->lastname}";
    }

    public function staycations()
    {
        return $this->belongsToMany(Staycation::class,'bookings','customer_id','staycation_id');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
