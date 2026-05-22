<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = ['status', 'start_date', 'end_date', 'booking_date', 'payment_status', 'member_id'];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function sessionBooking()
    {
        return $this->hasOne(SessionBooking::class);
    }

    public function trainerBooking()
    {
        return $this->hasOne(TrainerBooking::class);
    }

    public function bookingTypes()
    {
        return $this->hasMany(BookingType::class);
    }

}
