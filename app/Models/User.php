<?php

namespace App\Models;

use App\Models\Staycation\Booking;
use App\Models\Staycation\Staycation;
use App\Traits\UsesUuid;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, UsesUuid, HasRoles, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'firstname',
        'middlename',
        'lastname',
        'username',
        'email',
        'password',
        'date_of_birth',
        'mobile_number',
        'address',
        'created_by',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'mobile_number' => 'array',
        'date_of_birth' => 'date'
    ];

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }

    /**
     * @return string
     */
    public function getFullNameAttribute()
    {
        $fullName = "{$this->firstname} {$this->middlename} {$this->lastname}";
        return ucwords($fullName);
    }

    /**
     * retrieve all staycations of the owner
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function stayCations()
    {
        return $this->hasMany(Staycation::class,'owner_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function stayCationLists()
    {
        return $this->belongsToMany(Staycation::class,'staycation_user');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class,'booked_by');
    }


    public function adminlte_image()
    {
        return 'https://picsum.photos/300/300';
    }

    public function adminlte_desc()
    {
        $user = auth()->user();
        return ucwords($user->firstname.' '.$user->lastname);
    }

    public function adminlte_profile_url()
    {
        return 'profile/username';
    }
}
