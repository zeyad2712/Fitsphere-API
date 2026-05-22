<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['product_price', 'stock', 'name', 'image', 'description', 'category_id'];

    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'category_id');
    }

}
