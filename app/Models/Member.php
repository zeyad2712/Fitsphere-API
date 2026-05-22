<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'fitness_goal', 'weight', 'height', 'birth_date'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function feedbackGyms()
    {
        return $this->hasMany(FeedbackGym::class);
    }

    public function feedbackTrainers()
    {
        return $this->hasMany(FeedbackTrainer::class);
    }

    public function plans()
    {
        return $this->hasMany(Plan::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function logs()
    {
        return $this->hasMany(Log::class);
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function carts()
    {
        return $this->hasMany(Cart::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

}
