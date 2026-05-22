<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = ['reference_code', 'status', 'payment_id'];

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }

}
