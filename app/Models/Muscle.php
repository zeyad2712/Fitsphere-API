<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Muscle extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function workoutPlans()
    {
        return $this->belongsToMany(WorkoutPlan::class, 'workout_muscles', 'muscle_id', 'workout_id');
    }

    public function videos()
    {
        return $this->hasMany(Video::class);
    }

}
