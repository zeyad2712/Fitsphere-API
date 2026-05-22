<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AiService extends Model
{
    use HasFactory;

    protected $fillable = ['model', 'version', 'name'];

    public function logs()
    {
        return $this->hasMany(Log::class, 'service_id');
    }

}
