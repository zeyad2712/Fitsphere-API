<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = ['status', 'quantity', 'member_id'];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

}
