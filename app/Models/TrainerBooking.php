<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainerBooking extends Model
{
    use HasFactory;

    protected $fillable = ['booking_id', 'trainer_id', 'status'];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function trainer()
    {
        return $this->belongsTo(Trainer::class);
    }

}
