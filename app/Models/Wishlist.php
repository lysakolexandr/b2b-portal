<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','product_id'];

    public function getProductAttribute()
    {
        $product = Product::where(['id' => $this->product_id])->first();
        return $product;
    }
}