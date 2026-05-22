<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gym extends Model
{
    use HasFactory;

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    protected $fillable = [
        'user_id', 'city', 'street_name', 'description', 'closed_days', 
        'link', 'manager_name', 'manager_email', 'features', 
        'price_per_session', 'cover_image'
    ];

    protected $casts = [
        'features' => 'array',
    ];

    public function feedbackGyms()
    {
        return $this->hasMany(FeedbackGym::class);
    }

    public function sessionBookings()
    {
        return $this->hasMany(SessionBooking::class);
    }

}
