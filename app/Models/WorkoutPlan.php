<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkoutPlan extends Model
{
    use HasFactory;

    protected $fillable = ['plan_id', 'weight', 'exercise_name', 'sets_reps'];

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    public function muscles()
    {
        return $this->belongsToMany(Muscle::class, 'workout_muscles', 'workout_id', 'muscle_id');
    }

}
