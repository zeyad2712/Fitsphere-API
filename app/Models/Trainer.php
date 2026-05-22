<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trainer extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'experience_years', 'bio', 'birth_date', 
        'specialization', 'price_per_month', 'location', 
        'certifications', 'total_sessions', 'active_clients'
    ];

    protected $casts = [
        'certifications' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function feedbackTrainers()
    {
        return $this->hasMany(FeedbackTrainer::class);
    }

    public function plans()
    {
        return $this->hasMany(Plan::class);
    }

    public function trainerBookings()
    {
        return $this->hasMany(TrainerBooking::class);
    }

}
