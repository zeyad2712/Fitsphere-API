<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SessionBooking extends Model
{
    use HasFactory;

    protected $fillable = ['booking_id', 'session_date', 'duration', 'gym_id'];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function gym()
    {
        return $this->belongsTo(Gym::class);
    }

}
