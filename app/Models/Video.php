<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    use HasFactory;

    protected $fillable = ['url', 'title', 'description', 'duration', 'category_id', 'muscle_id'];

    public function category()
    {
        return $this->belongsTo(VideoCategory::class, 'category_id');
    }

    public function muscle()
    {
        return $this->belongsTo(Muscle::class);
    }

}
