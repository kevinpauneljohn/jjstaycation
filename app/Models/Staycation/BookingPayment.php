<?php

namespace App\Models\Staycation;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BookingPayment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'booking_id',
        'amount',
        'category',
        'created_by',
        'remarks',
        'confirmed',
        'file'
    ];

    protected static function booted()
    {
        static::saving(function($bookingPayment){
            $bookingPayment->created_by = auth()->user()->id;
        });
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}
