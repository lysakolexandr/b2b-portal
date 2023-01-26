<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Currency;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\UserSettings;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PDO;

class ApiTokenController extends Controller
{
    /**
     * Update the authenticated user's API token.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function update(Request $request)
    {
        $token = Str::random(36);

        $request->user()->forceFill([
            'api_token' => $token,
        ])->save();
        $route_currency = route('currency') . '?api_token=' . $token . '';
        $route_categories_ua = route('categories') . '?api_token=' . $token . '&lang=ua';
        $route_categories_ru = route('categories') . '?api_token=' . $token . '&lang=ru';
        $route_products_ua = route('products') . '?api_token=' . $token . '&lang=ua';
        $route_products_ru = route('products') . '?api_token=' . $token . '&lang=ru';
        $route_available = route('available') . '?api_token=' . $token . '';

        return [
            'token' => $token,
            'route_currency' => $route_currency,
            'route_categories_ua' => $route_categories_ua,
            'route_categories_ru' => $route_categories_ru,
            'route_products_ua' => $route_products_ua,
            'route_products_ru' => $route_products_ru,
            'route_available' => $route_available,
        ];
    }

    public function apiManual()
    {
        $token = Auth::user()->api_token;
        $user = auth()->user();
        $record = UserSettings::where(['customer_id' => $user->customer_code])->firstOrCreate();
        $locale = $record->language;
        if ($locale == 'ua') {
            $view = 'api.manual_ua';
        } else {
            $view = 'api.manual_ru';
        }
        $showFilter = false;
        return view(
            $view,
            compact(
                'token',
                'showFilter'
            )
        );
    }

    public function apiSettings(Request $request)
    {
        $token = Auth::user()->api_token;
        $route_currency = route('currency') . '?api_token=' . $token . '';
        $route_categories_ua = route('categories') . '?api_token=' . $token . '&lang=ua';
        $route_categories_ru = route('categories') . '?api_token=' . $token . '&lang=ru';
        $route_products_ua = route('products') . '?api_token=' . $token . '&lang=ua';
        $route_products_ru = route('products') . '?api_token=' . $token . '&lang=ru';
        $route_available = route('available') . '?api_token=' . $token . '';
        $showFilter = false;
        return view(
            'api.settings',
            compact(
                'token',
                'route_currency',
                'route_categories_ua',
                'route_categories_ru',
                'route_products_ua',
                'route_categories_ru',
                'route_products_ua',
                'route_products_ru',
                'route_available',
                'showFilter'
            )
        );
    }

    public function currency(Request $request)
    {
        $currency = Currency::all();
        $result = [];
        foreach ($currency as $item) {
            if ($item->code == 'грн') {
                $result['UAH'] = $item->rate;
            } else {
                $result[$item->code] = $item->rate;
            }
        }
        return response()->json($result);
    }

    public function available()
    {
        $result = [];
        $products = Product::all();
        foreach ($products as $item) {
            $product = [];
            $product['id'] = $item->id;
            $product['available'] = $item->available;
            $result[] = $product;
        }
        return response()->json($result);
    }

    public function categories(Request $request)
    {
        $lang = $request->get('lang');
        $result = [];
        $main_categories = Category::where(['parent_id' => 0])->get();
        foreach ($main_categories as $item) {
            $category_data = [];
            $category_data['id'] = $item->id;
            if ($lang == 'ua') {
                $name = $item->name;
            } else {
                $name = $item->name_ru;
            }
            $category_data['name'] = $name;
            $children = [];
            $children_categories = Category::where(['parent_id' => $item->id])->get();
            foreach ($children_categories as $child_item) {
                $children_data = [];
                $children_data['id'] = $child_item->id;
                if ($lang == 'ua') {
                    $name = $child_item->name;
                } else {
                    $name = $child_item->name_ru;
                }
                $children_data['name'] = $name;
                $children[] = $children_data;
            }
            $category_data['children'] = $children;

            $result[] = $category_data;
        }
        return response()->json($result);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function products2(Request $request)
    {
        $lang = $request->get('lang');
        return response()->view('api.json_products',compact('lang'))->header('Content-type', 'text/json');;
    }

    public function products(Request $request)
    {
        $lang = $request->get('lang');
        $products = Product::paginate(50);
        $result = [];
        foreach ($products as $item) {
            $product = [];
            $product['id'] = $item->id;
            $category = Category::where(['id' => $item->category_id])->first();
            if ($lang == 'ua') {
                $name = $item->name;
                $description = $item->description;
                if ($category!=null){
                    $category_name = $category->name;
                }else{
                    $category_name = '';
                }
            } else {
                $name = $item->name_ru;
                $description = $item->description_ru;
                if ($category!=null){
                    $category_name = $category->name_ru;
                }else{
                    $category_name = '';
                }
            }
            $product['name'] = $name;
            $product['brand'] = $item->brandName;
            $product['article'] = $item->article;
            $product['description'] = $description;
            $category = [];
            $category['id'] = $item->category_id;
            $category['name'] = $category_name;
            $product['category'] = $category;
            $product['price'] = round($item->personalPrice, 2);
            $product['price_currency'] = $item->personalPriceCurrency;
            $product['retail_price'] = round($item->retailPriceUah, 2);
            $product['retail_price_currency'] = 'UAH';
            $product['gallery'] = $this->arrayPictures($item);
            $product['local_stock'] = intval(str_replace('>', '', $item->myAmount));
            $product['center_stock'] = intval(str_replace('>', '', $item->centerAmount));

            $properties_array = [];

            if ($item->countryOfConsignmentName != '') {
                $elem = [];
                $elem['name'] = __('l.country_of_consignment', [], $lang);
                $elem['value'] = $item->countryOfConsignmentName;
                $properties_array[] = $elem;
            }

            if ($item->countryOfBrandRegistrationName != '') {
                $elem = [];
                $elem['name'] = __('l.country_of_brand_registration', [], $lang);
                $elem['value'] = $item->countryOfBrandRegistrationName;
                $properties_array[] = $elem;
            }

            if (json_decode($item->properties) != null) {
                $properties = json_decode($item->properties);
                usort($properties, function ($a, $b) {
                    return $a->sort - $b->sort;
                });
                foreach ($properties as $item2) {
                    $elem = [];
                    if ($item2->name != '') {
                        if ($item2->value != '') {
                            $elem['name'] = $item2->name;
                            $elem['value'] = $item2->value;
                            $properties_array[] = $elem;
                        }
                    }
                }
            }
            if ($item->weight != 0) {
                $elem = [];
                $elem['name'] = __('l.weight', [], $lang);
                $elem['value'] = $item->weight;
                $properties_array[] = $elem;
            }
            if ($item->volume != 0) {
                $elem = [];
                $elem['name'] = __('l.volume', [], $lang);
                $elem['value'] = $item->volume;
                $properties_array[] = $elem;
            }

            if (count($properties_array)>0){
                $product['properties'] = $properties_array;
            }
            $result[] = $product;
        }
        return response()->json($result);
    }

    public function arrayPictures($product)
    {
        $picture = [];
        foreach ($product['pictures'] as $value) {
            $picture[] = '' . $_SERVER['SERVER_NAME'] . '/' . str_replace('public', 'storage', $value);
        }
        return $picture;
    }

    public function getToken(Request $request, $email, $pass)
    {

        $user = User::where(['email' => $email])->first();
        $result = [];
        if ($user != null) {
            if ($user->pass == $pass) {
                $result['access_token'] = $user->api_token;
            } else {
                $result['ErrorCode'] = 'Wrong password';
            }
        } else {
            $result['ErrorCode'] = 'Wrong email';
        }
        return response()->json($result);
    }

    public function orders()
    {
        $orders = Order::where(['user_id' => Auth::user()->id])->paginate(20);
        return response()->json($orders);
    }
}
