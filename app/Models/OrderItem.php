<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends MainModel
{
    use HasFactory;

    protected $fillable = ['product_id'];

    public function getProductAttribute()
    {
        $product = Product::where(['id' => $this->product_id])->first();
        return $product;
    }

    public function getCurrencySymbolAttribute()
    {
        $currency = Currency::where(['id' => $this->currency])->first();
        if ($currency!=null){
            return $currency->symbol;
        }else{
            return '';
        }
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

}
