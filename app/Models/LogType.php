<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogType extends Model
{
    use HasFactory;

    protected $fillable = ['log_id', 'type'];

    public function log()
    {
        return $this->belongsTo(Log::class);
    }

}
