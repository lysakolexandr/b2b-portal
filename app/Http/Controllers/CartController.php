<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Cart;
use App\Models\Currency;
use App\Models\DeliveryPoint;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use NumberFormatter;
use Validator;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $products = Product::orderBy('id', 'desc')->get();
        $categories = Category::orderBy('id', 'desc')->where(['parent_id' => null])->get();
        $carts = Cart::where(['user_id' => Auth::user()->id])->get();

        $delivery_points = DeliveryPoint::where(['user_id' => Auth::user()->customer_code])->orderBy('sort', 'asc')->get();

        $showFilter = false;
        if ($request->ajax()) {
            return view('layouts.cart', compact('products', 'carts', 'delivery_points'));
        } else {
            return view('catalog.cart', compact('products', 'carts', 'delivery_points', 'showFilter'));
        };
    }

    public function clear()
    {
        $user_id = Auth::user()->id;
        $carts = Cart::where(['user_id' => $user_id])->get();
        foreach ($carts as $key => $item) {
            $item->delete();
        }

        return redirect('/');
    }

    public function MakeOrder(Request $request)
    {
        //dd($request->all());
        $messages = array(
            'delivery.required' => __('l.you_need_select_delivery'),
        );
        $validator = Validator::make($request->all(), [
            'delivery' => 'required',
        ],$messages);

        if ($validator->passes()) {

            $user_id = Auth::user()->id;
            $carts = Cart::where(['user_id' => $user_id])->get();
            if (count($carts) == 0) {
                return redirect('/');
            }
            $order = new Order();
            if ($request->get('delivery') == 0) {
                $order->delivery_type = 1; //1 - самовивіз, 2 - доставка в пункт доставки, 3 - нова пошта
            } else {
                $order->delivery_type = 2; //1 - самовивіз, 2 - доставка в пункт доставки, 3 - нова пошта
                $order->delivery_point_id = $request->get('delivery');
            }

            $order->comment = $request->get('comment');
            $order->user_id = $user_id;
            $order->save();
            foreach ($carts as $item) {
                $product = Product::where(['id' => $item->product_id])->first();
                $order_item = new OrderItem();
                $order_item->product_id = $item->product_id;
                $order_item->qty = $item->qty;
                $order_item->price = $product->personalPrice;
                $order_item->currency = $product->personalPriceCurrencyId;
                $order_item->order_id = $order->id;
                $order_item->save();

                $item->delete();
            }



            $showFilter = false;
            return redirect('/finish');
        }

        return response()->json(['error' => $validator->errors()->all()]);
    }

    public function finish()
    {
        $showFilter = false;
        dd($showFilter);
        return view('catalog.finish', compact('showFilter'));
    }

    public function add(Request $request)
    {
        $product_id = $request->product_id;
        if ($product_id <> null) {
            $user_id = Auth::user()->id;
            $cart = Cart::firstOrNew(
                [
                    'product_id' => $product_id,
                    'user_id' => $user_id,
                ]
            );
            $cart->qty = $cart->qty + $request->qty;
            $cart->user_id = $user_id;
            $cart->price = Product::where(['id' => $product_id])->first()->personalPrice;
            $cart->save();
        } elseif ($request->qty == 'delete') {
            $row = Cart::find($request->row_id);
            $row->delete();
        }


        return $this::getCartCount();
    }

    public function set(Request $request)
    {
        //dd($request->row_id);
        $row_id = $request->row_id;
        if ($row_id <> null) {
            $cart = Cart::firstOrNew(
                ['id' => $row_id]
            );
            $cart->qty = $request->qty;
            $cart->user_id = Auth::user()->id;
            $cart->save();
        };
        return $this::getCartCount();
    }

    public static function getCartCount()
    {
        if (Auth::user() == null) {
            return 0;
        }
        $carts = Cart::where(
            'user_id',
            Auth::user()->id
        )->get();
        $res = 0;
        foreach ($carts as $item) {
            $res += 1;
        }

        return $res;
    }

    public static function getSumUah()
    {
        $carts = Cart::where(
            'user_id',
            Auth::user()->id
        )->get();
        $res = 0;
        foreach ($carts as $item) {
            if ($item->product->personalPriceCurrency == 'UAH') {
                $res += ($item->product->personal_price * $item->qty);
            }
        }
        $fmt = new NumberFormatter('en-CA', NumberFormatter::PATTERN_DECIMAL, "0.00"); //, "* #####.00 ;(* #####.00)");
        $res = $fmt->format($res);
        return $res;
    }

    public static function getTotalSum()
    {
        $uah = 0;
        $carts = Cart::where(
            'user_id',
            Auth::user()->id
        )->get();
        foreach ($carts as $item) {
            if ($item->product->personalPriceCurrency == 'UAH') {
                $uah += ($item->product->personal_price * $item->qty);
            } elseif ($item->product->personalPriceCurrency == 'USD') {
                $uah += ($item->product->personal_price * $item->qty * Currency::getUSD());
            } elseif ($item->product->personalPriceCurrency == 'EUR') {
                $uah += ($item->product->personal_price * $item->qty * Currency::getEUR());
            };
        }
        $fmt = new NumberFormatter('en-CA', NumberFormatter::PATTERN_DECIMAL, "0.00");
        $uah = $fmt->format($uah);
        return $uah;
    }

    public static function getTotalSumRetail()
    {
        $uah = 0;
        $carts = Cart::where(
            'user_id',
            Auth::user()->id
        )->get();
        foreach ($carts as $item) {
            $uah += ($item->product->retailPriceUah * $item->qty);
        }
        $fmt = new NumberFormatter('en-CA', NumberFormatter::PATTERN_DECIMAL, "0.00");
        $uah = $fmt->format($uah);
        return $uah;
    }

    public static function getSumUsd()
    {
        $carts = Cart::where(
            'user_id',
            Auth::user()->id
        )->get();
        $res = 0;
        foreach ($carts as $item) {
            if ($item->product->personalPriceCurrency == 'USD') {
                $res += ($item->product->personal_price * $item->qty);
            }
        }
        $fmt = new NumberFormatter('en-CA', NumberFormatter::PATTERN_DECIMAL, "0.00");
        $res = $fmt->format($res);
        return $res;
    }

    public static function getSumEur()
    {
        $carts = Cart::where(
            'user_id',
            Auth::user()->id
        )->get();
        $res = 0;
        foreach ($carts as $item) {
            if ($item->product->personalPriceCurrency == 'EUR') {
                $res += ($item->product->personal_price * $item->qty);
            }
        }
        $fmt = new NumberFormatter('en-CA', NumberFormatter::PATTERN_DECIMAL, "0.00");
        $res = $fmt->format($res);
        return $res;
    }
}
