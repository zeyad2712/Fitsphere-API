<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rm1Log extends Model
{
    use HasFactory;

    protected $fillable = ['log_id', 'max_weight', 'max_rep', 'muscle_name', 'max_rep_weight'];

    public function log()
    {
        return $this->belongsTo(Log::class);
    }

}
