<?php

namespace App\Helpers;

use App\Models\Brand;
use App\Models\Product;
use App\Models\ProductsInStockGroup;
use Illuminate\Support\Facades\Auth;

class AppHelper
{
    public function myAmount($id)
    {
        //$product = Product::findOrFail($id);
        $stock_group_id = Auth::user()->stock_group_id;
        $var = ProductsInStockGroup::where(['product_id' => $id, 'stock_group_id' => $stock_group_id])->first();
        $qty = 0;
        if ($var != null) {
            $qty = $var->qty;
        }
        return $qty;
        //return $product->myAmount;
    }

    public function brand($id)
    {
        $brand = Brand::findOrFail($id);


        return $brand->name;
    }


    public static function instance()
    {
        return new AppHelper();
    }
}
