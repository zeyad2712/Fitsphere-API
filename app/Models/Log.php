<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    use HasFactory;

    protected $fillable = ['notes', 'date', 'target_value', 'achieved_value', 'member_id', 'service_id'];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function aiService()
    {
        return $this->belongsTo(AiService::class, 'service_id');
    }

    public function weightLog()
    {
        return $this->hasOne(WeightLog::class);
    }

    public function rm1Log()
    {
        return $this->hasOne(Rm1Log::class);
    }

    public function logTypes()
    {
        return $this->hasMany(LogType::class);
    }

}
