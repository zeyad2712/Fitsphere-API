<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NutritionPlan extends Model
{
    use HasFactory;

    protected $fillable = ['plan_id', 'meals', 'fats', 'carbs', 'calories', 'protein'];

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

}
