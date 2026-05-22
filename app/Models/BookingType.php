<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingType extends Model
{
    use HasFactory;

    protected $fillable = ['booking_id', 'type'];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

}
