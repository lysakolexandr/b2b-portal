<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;


//use File;

class ProductInPricelist extends MainModel
{
    use HasFactory;

    protected $fillable = ['product_id','price_id'];

}
