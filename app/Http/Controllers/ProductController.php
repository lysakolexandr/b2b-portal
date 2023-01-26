<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Mail\RegisterMail;
use App\Models\Order;
use App\Models\UserSettings;
use Response;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail as Mail;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Console\Input\Input;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    private static function main_categories()
    {
        return Category::orderBy('sort', 'asc')->where(['parent_id' => 0])->get();
    }

    public function smartSearch(Request $request){
        $query = $request->get('q');

        // If the input is empty, return an error response
        if(!$query && $query == '') return Response::json(array(), 400);

		$products = Product::where('active', true)
			->where('name','like','%'.$query.'%')
            ->orWhere('code','like','%'.$query.'%')
			->orderBy('name','asc')
			->take(7)
			->get(array('id','name','name_ru','category_id'))->toArray();

		// $categories = Category::where('name','like','%'.$query.'%')
		// 	->take(5)
		// 	->get(array('name'))
		// 	->toArray();

		// // Data normalization
		// $categories = $this->appendValue($categories, url('img/icons/category-icon.png'),'category_id');

		$products 	= $this->appendURL($products, 'product');
		$categories  = [];//$this->appendURL($categories, 'categories');

		// Add type of data to each item of each set of results
		$products = $this->appendValue($products, 'product', 'class');
        $products = $this->appendName($products);
        $products = $this->appendCode($products);
        $products = $this->appendPicture($products);
		// $categories = $this->appendValue($categories, 'category', 'class');

		// Merge all data into one array
		$data = array_merge($products, $categories);

		return Response::json(array(
			'data'=>$data
		));
    }

    public function appendName($data){
        foreach ($data as $key => & $item) {
            $product = Product::find($item['id']);
			$item['name'] = $product->name;
		}
		return $data;
    }
    public function appendCode($data){
        foreach ($data as $key => & $item) {
            $product = Product::find($item['id']);
			$item['code'] = $product->code;
		}
		return $data;
    }

    public function appendPicture($data){
        foreach ($data as $key => & $item) {
            $product = Product::find($item['id']);
			$item['picture'] = '.././'.Str::replaceFirst('public', 'storage', $product->mainPicture);
		}
		return $data;
    }

    public function appendValue($data, $type, $element)
	{
		// operate on the item passed by reference, adding the element and type
		foreach ($data as $key => & $item) {
			$item[$element] = $type;
		}
		return $data;
	}

	public function appendURL($data, $prefix)
	{
		// operate on the item passed by reference, adding the url based on slug
		foreach ($data as $key => & $item) {
			$item['url'] = url($prefix.'/' .$item['id']         );
		}
		return $data;
	}


    public function products(Request $request, $id = null)
    {

        $hidePrice = $request->get('hide_price');
        $ids = $request->get('ids');
        $available = $request->get('available');
        $sort = $request->get('sort');
        $search = $request->get('q');

        if ($id===null && $search===null){
            return redirect()->route('home');
        }

        $count = $request->get('count');


        $userSettings = UserSettings::where('customer_id', Auth::user()->customer_code)->first();
        $sortSettings = 0;
        $countSettings = 10;
        $availableSettings = 'false';
        if ($userSettings != null) {
            $sortSettings = $userSettings->sort;
            $countSettings = $userSettings->products_on_page;
            $availableSettings = $userSettings->available;
        }
        if ($sort == null) {
            $sort = $sortSettings;
        }
        if ($count == null) {
            $count = $countSettings;
        }
        if ($available == null) {
            $available = $availableSettings;
        }

        if (($hidePrice != null)) {

            $user = auth()->user();
            $user->hide_price = $hidePrice;
            $user->save();

            // $userSettings = UserSettings::where('customer_id', Auth::user()->customer_code)->first();
            // $userSettings->hide_price = $hidePrice;
            // $userSettings->save();
        }
        $userSettings = UserSettings::where('customer_id', Auth::user()->customer_code)->first();
        if ($userSettings!=null){
            if ($sort != $userSettings->sort) {
                $userSettings->sort = $sort;
                $userSettings->save();
            }
            if ($count != $userSettings->products_on_page){
                $userSettings->products_on_page = $count;
                $userSettings->save();
            }
            if ($available != $userSettings->available){
                $userSettings->available = $available;
                $userSettings->save();
            }
            $sort = $userSettings->sort;
            $count = $userSettings->products_on_page;
            $available = $userSettings->available;
        }



        if ($sort == 0) {
            $locale = App::getLocale();
            if ($locale == 'ru') {
                $sort_field = 'name_ru';
            } else {
                $sort_field = 'name';
            }
            $sort_direction = 'asc';
        } elseif ($sort == 1) {
            $sort_field = 'price';
            $sort_direction = 'asc';
        } else {
            $sort_field = 'price';
            $sort_direction = 'desc';
        }

        if ($search != null) {
            $query = mb_strtolower($search, 'UTF-8');
            $arr = explode(" ", $query);
            $query = [];
            foreach ($arr as $word) {
                $len = mb_strlen($word, 'UTF-8');
                switch (true) {
                    case ($len <= 3): {
                            $query[] = $word . "*";
                            break;
                        }
                    case ($len > 3 && $len <= 6): {
                            $query[] = mb_substr($word, 0, -1, 'UTF-8') . "*";
                            break;
                        }
                    case ($len > 6 && $len <= 9): {
                            $query[] = mb_substr($word, 0, -2, 'UTF-8') . "*";
                            break;
                        }
                    case ($len > 9): {
                            $query[] = mb_substr($word, 0, -3, 'UTF-8') . "*";
                            break;
                        }
                    default: {
                            break;
                        }
                }
            }
            $query = array_unique($query, SORT_STRING);
            $qQeury = implode(" ", $query);
        }


        $stock_group_id = Auth::user()->stock_group_id;
        $products = Product::leftJoin('products_in_stock_groups', 'products.id', '=', 'products_in_stock_groups.product_id')
            ->select('products.*', 'products_in_stock_groups.qty')
            ->where(
                function ($query) use ($stock_group_id) {
                    $query->where('stock_group_id', '=', $stock_group_id)
                        ->orWhereNull('stock_group_id');
                }
            )
            ->where('active',1)
            ->orderBy($sort_field, $sort_direction);


        $all_products_ob = Product::leftJoin('products_in_stock_groups', 'products.id', '=', 'products_in_stock_groups.product_id')
        ->select('products.*', 'products_in_stock_groups.qty')
            ->where(
                function ($query) use ($stock_group_id) {
                    $query->where('stock_group_id', '=', $stock_group_id)
                        ->orWhereNull('stock_group_id');
                }
            )
            ->where('active',1)
            ->orderBy($sort_field, $sort_direction);

        $filtered_products_ob = Product::leftJoin('products_in_stock_groups', 'products.id', '=', 'products_in_stock_groups.product_id')
        ->select('products.*', 'products_in_stock_groups.qty')
            ->where(
                function ($query) use ($stock_group_id) {
                    $query->where('stock_group_id', '=', $stock_group_id)
                        ->orWhereNull('stock_group_id');
                }
            )
            ->where('active',1)
            ->orderBy($sort_field, $sort_direction);
        if ($available == 'true') {
            $products = $products->where('products_in_stock_groups.qty', '>', '0');
            $filtered_products_ob =$filtered_products_ob->where('products_in_stock_groups.qty', '>', '0');
            $all_products_ob =$all_products_ob->where('products_in_stock_groups.qty', '>', '0');
        }
        if ($search != null) {
            $products = $products->where(
                function ($query) use ($search) {
                    $query->where('products.name', 'like', '%' . $search . '%')
                        ->orWhere('products.name_ru', 'like', '%' . $search . '%')
                        ->orWhere('products.code', 'like', '%' . $search . '%');
                }
            );
            $all_products_ob = $all_products_ob->where(
                function ($query)  use ($search) {
                    $query->where('products.name', 'like', '%' . $search . '%')
                        ->orWhere('products.name_ru', 'like', '%' . $search . '%')
                        ->orWhere('products.code', 'like', '%' . $search . '%');
                }
            );
            $filtered_products_ob = $filtered_products_ob->where(
                function ($query)  use ($search) {
                    $query->where('products.name', 'like', '%' . $search . '%')
                        ->orWhere('products.name_ru', 'like', '%' . $search . '%')
                        ->orWhere('products.code', 'like', '%' . $search . '%');
                }
            );
            if ($ids != null) {// && $ids != "999999999") {
                $ids = explode(",", $ids);
                $products = $products->whereIn('products.id', $ids);
                $all_products_ob = $all_products_ob->whereIn('products.id', $ids);
                $filtered_products_ob = $filtered_products_ob->whereIn('products.id', $ids);
            }
        } elseif ($ids != null){// && $ids != "999999999") {
            $ids = explode(",", $ids);
            $products = $products->whereIn('products.id', $ids)->where(['products.category_id' => $id]);
            $all_products_ob = $all_products_ob->where(['products.category_id' => $id]);
            $filtered_products_ob = $filtered_products_ob->where(['products.category_id' => $id])->whereIn('products.id', $ids);
        } else {
            $products = $products->where(['products.category_id' => $id]);
            $all_products_ob = $all_products_ob->where(['products.category_id' => $id]);
            $filtered_products_ob = $filtered_products_ob->where(['products.category_id' => $id]);
        }
        //echo $products->toSql();
        $products = $products->paginate($count);
        $all_products_ob = $all_products_ob->get();
        $filtered_products_ob = $filtered_products_ob->get();


        $all_products = json_encode($all_products_ob);
        $filtered_products = json_encode($filtered_products_ob);
        $categories = $this->main_categories();
        $category = Category::where(['id' => $id])->first();
        if ($search != null) {
            $main_category = null;
            $local_category = null;
            $main_category_id = null;
        } else {
            $main_category = Category::where(['id' => $category->parent_id])->first()->name;
            $local_category = $category->name;
            $main_category_id = $category->parent_id;
        }
        $local_category_id = $id;
        if ($request->ajax()) {
            return view('layouts.products_body', compact('products', 'local_category', 'main_category', 'sort', 'local_category_id', 'all_products', 'filtered_products', 'search','count','main_category_id'));
        }
        $showFilter = true;
        return view('catalog.products', compact('products', 'local_category', 'main_category', 'sort', 'local_category_id', 'all_products', 'filtered_products', 'search','showFilter','count','main_category_id'));
    }

    public function product(Request $request, $id)
    {
        $products = Product::orderBy('id', 'desc')->get();
        $categories = $this->main_categories();
        $product = Product::where(['id' => $id])->first();
        $showFilter = false;
        return view('catalog.product', compact('products', 'product','showFilter'));
    }

    public function filter(Request $request)
    {
        $id = $request->get('id');
        $hidePrice = $request->get('hide_price');
        $ids = $request->get('ids');
        $available = $request->get('available');
        $sort = $request->get('sort');
        $search = $request->get('q');

        if ($sort == null) {
            $sort = 0;
        }
        if ($hidePrice == 1) {
            $userSettings = UserSettings::where('customer_id', Auth::user()->customer_code)->first();
            if ($userSettings->hide_price == 1) {
                $userSettings->hide_price = 0;
            } else {
                $userSettings->hide_price = 1;
            }
            $userSettings->save();
        }

        $userSettings = UserSettings::where('customer_id', Auth::user()->customer_code)->first();
        if ($sort != $userSettings->sort) {


            $userSettings->sort = $sort;

            $userSettings->save();
        }

        if ($sort == 0) {
            $sort_field = 'name';
            $sort_direction = 'asc';
        } elseif ($sort == 1) {
            $sort_field = 'price';
            $sort_direction = 'asc';
        } else {
            $sort_field = 'price';
            $sort_direction = 'desc';
        }

        if ($search != null) {
            $query = mb_strtolower($search, 'UTF-8');
            $arr = explode(" ", $query);
            $query = [];
            foreach ($arr as $word) {
                $len = mb_strlen($word, 'UTF-8');
                switch (true) {
                    case ($len <= 3): {
                            $query[] = $word . "*";
                            break;
                        }
                    case ($len > 3 && $len <= 6): {
                            $query[] = mb_substr($word, 0, -1, 'UTF-8') . "*";
                            break;
                        }
                    case ($len > 6 && $len <= 9): {
                            $query[] = mb_substr($word, 0, -2, 'UTF-8') . "*";
                            break;
                        }
                    case ($len > 9): {
                            $query[] = mb_substr($word, 0, -3, 'UTF-8') . "*";
                            break;
                        }
                    default: {
                            break;
                        }
                }
            }
            $query = array_unique($query, SORT_STRING);
            $qQeury = implode(" ", $query);
        }
        $all_products_ob = Product::orderBy($sort_field, $sort_direction)->where(['category_id' => $id])->get();
        $filtered_products_ob = Product::orderBy($sort_field, $sort_direction)->where(['category_id' => $id])->get();
        $all_products = json_encode($all_products_ob);
        $filtered_products = json_encode($filtered_products_ob);

        return $all_products;
    }

    public function list(Request $request)
    {
        if ($request->q != '') {
            $products = Product::where('name', 'like', '%' . $request->q . '%')->orWhere('code', 'like', '%' . $request->q . '%')
                ->orWhere('name_ru', 'like', '%' . $request->q . '%')->take(100)->get();
            $res = '{
            "items": [';
            $first = true;
            foreach ($products as $item) {
                if (!$first) {
                    $res .= ',';
                }
                $name = str_replace('"', "", $item->name);
                $first = false;
                $res .= '{
                "id": "' . $item->id . '",
                "text": "' . $name . '",
                "price": "' . $item->personal_price . '",
                "currency": "' . $item->personal_price_currency . '",
                "qty": "' . 22 . '"
              }';
            }
            $res .= '],"pagination": {
            "more": false
          }
        }';
            return $res;
        } else {
            return '';
        }
    }


    public function delivery()
    {
        $products = Product::orderBy('id', 'desc')->get();
        $categories = $this->main_categories();
        return view('catalog.delivery', compact('products'));
    }

    public function drafts()
    {
        $products = Product::orderBy('id', 'desc')->get();
        $categories = $this->main_categories();
        return view('catalog.drafts', compact('products'));
    }



    public function OrderLists()
    {
        // $detalis = [
        //     'title' => 'Register',
        //     'body' => 'Your password 222',
        // ];
        // $mail = Mail::to('lysak.olexandr@gmail.com');
        // $mail_body = new RegisterMail($detalis);
        // $res = $mail->send($mail_body);

        //dd($mail);
        //die();

        $orders = Order::where(['user_id'=>Auth::user()->id])->get();


        return view('catalog.order_list', compact('orders'));
    }

    public function addPictures(Request $request)
    {
        $post_arr = $request->request->all();
        $f_string = $post_arr['f'];
        //dd($f_string);

        // $par = $request->post()["f"];
        // $par = str_replace(PHP_EOL,'',$par);
        //echo $par;
        //$f = json_decode($request["f"]);
        $f = json_decode($f_string);
        //dd($f);
        $product_id = $f->product_id;
        $exist = Storage::disk('local')->has('/public/img/products/'.$product_id);
        if($exist){
            Storage::deleteDirectory('/public/img/products/'.$product_id);
        }
        foreach($f->pictures as $pic){
            $imageName = '/public/img/products/'.$product_id.'/'.$pic->name;

            $exist = Storage::disk('local')->has('/public/img/products/'.$product_id);
            if(!$exist){
                Storage::disk('local')->makeDirectory('/public/img/products/'.$product_id);
            }
            $image = str_replace(' ', '+', $pic->data);
            Storage::disk('local')->put($imageName, base64_decode($image));
        }
        return true;
    }

    public function addCertificates(Request $request)
    {
        $par = $request["f"];
        $par = str_replace(PHP_EOL,'',$par);
        //echo $par;
        $f = json_decode($request["f"]);
        var_dump($f);
        $doc_id = $f->doc_id;
        foreach($f->pictures as $pic){
            $imageName = '/public/img/certificates/'.$doc_id.'/'.$pic->name;

            $exist = Storage::disk('local')->has('/public/img/certificates/'.$doc_id);
            if(!$exist){
                Storage::disk('local')->makeDirectory('/public/img/certificates/'.$doc_id);
            }
            $image = str_replace(' ', '+', $pic->data);
            Storage::disk('local')->put($imageName, base64_decode($image));
        }
        return true;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        //
    }
}
