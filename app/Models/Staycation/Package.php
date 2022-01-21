<?php

namespace App\Models\Staycation;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Package extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'staycation_id',
        'name',
        'pax',
        'amount',
        'created_by',
        'days',
        'time_in',
        'time_out',
        'color'
    ];

    protected static function booted(){
        static::saving(function($package){
            $package->created_by = auth()->user()->id;
        });
    }

    public function staycation()
    {
        return $this->belongsTo(Staycation::class);
    }
}
