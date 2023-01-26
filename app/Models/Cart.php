<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends MainModel
{
    use HasFactory;

    protected $fillable = ['product_id'];

    public function getProductAttribute()
    {
        $product = Product::where(['id' => $this->product_id])->first();
        return $product;
    }

}
