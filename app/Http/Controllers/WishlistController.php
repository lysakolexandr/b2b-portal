<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\UserSettings;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function index(Request $request)
    {
        $hidePrice = $request->get('hide_price');
        $sort = $request->get('sort');
        $available = $request->get('available');
        $ids = $request->get('ids');
        $userSettings = UserSettings::where('customer_id', Auth::user()->customer_code)->first();
        $sortSettings = 0;
        $count = $request->get('count');

        if ($userSettings != null) {
            $sortSettings = $userSettings->sort;
            $countSettings = $userSettings->products_on_page;
        }
        if ($sort == null) {
            $sort = $sortSettings;
        }
        if ($sort == null) {
            $sort = 0;
        }
        if ($count == null) {
            $count = $countSettings;
        }
        if ($count == null) {
            $count = 10;
        }
        if ($count != $userSettings->products_on_page){
            $userSettings->products_on_page = $count;

            $userSettings->save();
        }
        $count = $userSettings->products_on_page;
        if ($sort == 0) {
            $sort_field = 'products.name';
            $sort_direction = 'asc';
        } elseif ($sort == 1) {
            $sort_field = 'products.price';
            $sort_direction = 'desc';
        } else {
            $sort_field = 'products.price';
            $sort_direction = 'asc';
        }
        if (($hidePrice != null)) {
            $user = auth()->user();
            $user->hide_price = $hidePrice;
            $user->save();

            // $userSettings = UserSettings::where('customer_id', Auth::user()->customer_code)->first();
            // $userSettings->hide_price = $hidePrice;
            // $userSettings->save();
        }
        $wishlists = Wishlist::leftJoin('products_in_stock_groups', 'wishlists.product_id', '=', 'products_in_stock_groups.product_id')
            ->leftJoin('products','wishlists.product_id','products.id')
            ->select('wishlists.*', 'products_in_stock_groups.qty','products.name as name','products.price as price')
            ->where('stock_group_id', '=', Auth::user()->stock_group_id)
            ->where('user_id','=',Auth::user()->id)
            ->orderBy($sort_field, $sort_direction);

        if ($available == 'true') {
            $wishlists = $wishlists->where('products_in_stock_groups.qty', '>', '0');
        }
        $all_wishlists_ob = Wishlist::orderBy('id');
        $all_wishlists_ob = Product::leftJoin('wishlists','products.id','wishlists.product_id')
            ->select('products.*', 'wishlists.id as wishlists_id')
            ->where('wishlists.user_id', '=', Auth::user()->id)
            ->orderBy($sort_field, $sort_direction);
        $filtered_wishlists_ob = Wishlist::orderBy('id');
        $filtered_wishlists_ob = Product::leftJoin('wishlists','products.id','wishlists.product_id')
            ->select('products.*', 'wishlists.id as wishlists_id')
            ->where('wishlists.user_id', '=', Auth::user()->id)
            ->orderBy($sort_field, $sort_direction);
        if ($ids != null && $ids != "999999999") {
            $ids = explode(",", $ids);
            $wishlists = $wishlists->whereIn('products.id', $ids);
            $filtered_wishlists_ob = $filtered_wishlists_ob->whereIn('products.id', $ids);
        }
        // echo $wishlists->toSql();
        // die();
        $wishlists = $wishlists->paginate($count);
        $all_wishlists_ob = $all_wishlists_ob->get();
        $filtered_wishlists_ob = $filtered_wishlists_ob->get();

        $all_wishlists = json_encode($all_wishlists_ob);
       // echo($all_wishlists);
        //die();
        $filtered_wishlists = json_encode($filtered_wishlists_ob);
        $user_id = Auth::user()->id;
        $products = Wishlist::where(['user_id' => $user_id])->paginate($count);
        if ($request->ajax()) {
            return view('layouts.wishlist_body', compact('wishlists','all_wishlists','filtered_wishlists','count'));
        }
        $showFilter = true;
        return view('catalog.wishlists', compact('wishlists','all_wishlists','filtered_wishlists', 'showFilter' ,'count'));
    }

    public function add(Request $request)
    {
        $product_id = $request->product_id;
        if ($product_id <> null) {
            $wishlist = Wishlist::where(
                ['product_id' => $product_id],
                ['user_id' => Auth::user()->id]
            )->first();
            if ($wishlist == null) {
                $wishlist = Wishlist::firstOrCreate(
                    ['product_id' => $product_id],
                    ['user_id' => Auth::user()->id]
                );
            } else {
                $wishlist->delete();
            }
        }
        return $this::getWishlistCount();
    }

    public static function getWishlistCount()
    {
        if (Auth::user() == null) {
            return 0;
        }
        $carts = Wishlist::where(
            'user_id',
            Auth::user()->id
        )->get();
        $res = 0;
        foreach ($carts as $item) {
            $res += 1;
        }
        return $res;
    }

    public function delete(Request $request)
    {
        $hidePrice = $request->get('hide_price');
        $sort = $request->get('sort');
        $available = $request->get('available');
        $ids = $request->get('ids');
        if ($sort == null) {
            $sort = 0;
        }
        if ($sort == 0) {
            $sort_field = 'products.name';
            $sort_direction = 'asc';
        } elseif ($sort == 1) {
            $sort_field = 'products.price';
            $sort_direction = 'desc';
        } else {
            $sort_field = 'products.price';
            $sort_direction = 'asc';
        }
        $wishlists = Wishlist::leftJoin('products_in_stock_groups', 'wishlists.product_id', '=', 'products_in_stock_groups.product_id')
            ->leftJoin('products','wishlists.product_id','products.id')
            ->select('wishlists.*', 'products_in_stock_groups.qty','products.name as name','products.price as price')
            ->where('stock_group_id', '=', Auth::user()->stock_group_id)
            ->orderBy($sort_field, $sort_direction);

        if ($available == 'true') {
            $wishlists = $wishlists->where('products_in_stock_groups.qty', '>', '0');
        }
        $all_wishlists_ob = Wishlist::orderBy('id');
        $all_wishlists_ob = Product::leftJoin('wishlists','products.id','wishlists.product_id')
            ->select('products.*', 'wishlists.id as wishlists_id')
            ->where('wishlists.user_id', '=', Auth::user()->id)
            ->orderBy($sort_field, $sort_direction);
        $filtered_wishlists_ob = Wishlist::orderBy('id');
        $filtered_wishlists_ob = Product::leftJoin('wishlists','products.id','wishlists.product_id')
            ->select('products.*', 'wishlists.id as wishlists_id')
            ->where('wishlists.user_id', '=', Auth::user()->id)
            ->orderBy($sort_field, $sort_direction);
        if ($ids != null && $ids != "999999999") {
            $ids = explode(",", $ids);
            $wishlists = $wishlists->whereIn('products.id', $ids);
            $filtered_wishlists_ob = $filtered_wishlists_ob->whereIn('products.id', $ids);
        }
        $wishlists = $wishlists->paginate(10);
        $all_wishlists_ob = $all_wishlists_ob->get();
        $filtered_wishlists_ob = $filtered_wishlists_ob->get();

        $all_wishlists = json_encode($all_wishlists_ob);
        $filtered_wishlists = json_encode($filtered_wishlists_ob);

        $product_id = $request->product_id;
        if ($product_id <> null) {
            $wishlist = Wishlist::where(
                ['product_id' => $product_id],
                ['user_id' => Auth::user()->id]
            )->first();
            if ($wishlist != null) {
                $wishlist->delete();
            }
        }
        $user_id = Auth::user()->id;
        $wishlists = Wishlist::where(['user_id' => $user_id])->paginate(1000);
        return view('layouts.wishlist_body', compact('wishlists','all_wishlists','filtered_wishlists'));
        // return $this::getWishlistCount();
    }
}
