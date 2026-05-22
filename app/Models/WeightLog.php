<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WeightLog extends Model
{
    use HasFactory;

    protected $fillable = ['log_id', 'body_fat_pct', 'weight_kg', 'muscle_change_kg'];

    public function log()
    {
        return $this->belongsTo(Log::class);
    }

}
