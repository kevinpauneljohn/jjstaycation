<?php

namespace App\Models\Staycation;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Booking extends Model implements \Acaronlex\LaravelCalendar\Event
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'customer_id',
        'staycation_id',
        'total_amount',
        'start',
        'end',
        'pax',
        'remarks',
        'occasion',
        'status',
        'isAllDay',
        'backgroundColor',
        'booked_by',
        'cancelled_by'
    ];

    protected static function booted(){
        static::saving(function($booking){
            $booking->booked_by = auth()->user()->id;
        });
    }

    protected $dates = ['start', 'end'];


    /**
     * Get the event's id number
     *
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Get the event's title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Is it an all day event?
     *
     * @return bool
     */
    public function isAllDay()
    {
        return (bool)$this->all_day;
    }

    /**
     * Get the start time
     *
     * @return DateTime
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * Get the end time
     *
     * @return DateTime
     */
    public function getEnd()
    {
        return $this->end;
    }

    /**
     * @param $query
     * @param $start_date
     * @param $end_date
     * @return mixed
     */
    public function scopeOverlapping($query, $start_date, $end_date)
    {
        return $query->whereBetween('start', [$start_date, $end_date])
            ->orWhereBetween('end', [$start_date, $end_date])
            ->orWhereRaw('? BETWEEN start and end', [$start_date])
            ->orWhereRaw('? BETWEEN start and end', [$end_date]);
    }

    public function user()
    {
        return $this->belongsTo(User::class,'booked_by');
    }

    public function getCustomerFullNameAttribute()
    {
        $customer = Customer::find($this->customer_id);
        return $customer->firstname.' '.$customer->lastname;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function bookingPayments(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(BookingPayment::class);
    }

    public function staycation()
    {
        return $this->belongsTo(Staycation::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
