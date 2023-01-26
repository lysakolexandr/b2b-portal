<?php

namespace App\Models;

use App\Http\Controllers\CurrencyController;
use Facade\FlareClient\Stacktrace\File;
use Faker\Provider\File as ProviderFile;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Testing\File as TestingFile;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Console\Helper\FormatterHelper;

//use File;

class Product extends MainModel
{
    use HasFactory;

    protected $visible = [
        'id',
        'properties_filter',
        //'available'
    ];

    //protected $appends = ['available'];

    public function getDescriptionAttribute()
    {
        $locale = App::getLocale();

        if ($locale == 'ru') {
            $object = DB::table($this->table)->where('id', $this->id)->first();
            $description = $object->description_ru;
            return $description;
        } else {
            $object = DB::table($this->table)->where('id', $this->id)->first();
            $description = $object->description;
            return $description;
        }
    }

    public function getPropertiesAttribute()
    {
        $locale = App::getLocale();
        if ($locale == 'ru') {
            $object = DB::table($this->table)->where('id', $this->id)->first();
            $properties = $object->properties_ru;
        } else {
            $object = DB::table($this->table)->where('id', $this->id)->first();
            $properties = $object->properties;
        }

        return $properties;
    }

    public function getPropertiesFilterAttribute()
    {
        $locale = App::getLocale();
        if ($locale == 'ru') {
            $object = DB::table($this->table)->where('id', $this->id)->first();
            $properties = $object->properties_filter_ru;
            return $properties;
        } else {
            $object = DB::table($this->table)->where('id', $this->id)->first();
            $properties = $object->properties_filter;
            return $properties;
        }
    }

    public function getAvailableAttribute()
    {
        $user = Auth::user();

        if ($user->parent_id!=null){
            $parent_user = User::where(['id'=>$user->parent_id])->first();
            $customer_code = $parent_user->customer_code;
        }else{
            $customer_code = $user->customer_code;
        }
        $stock_group_id = UserSettings::where(['customer_id' => $customer_code])->first()->stock_group_id;

        $products_in_stock_group = ProductsInStockGroup::where(['stock_group_id'=>$stock_group_id,'product_id'=>$this->id])->first();
        $amount = 0;

        if ($products_in_stock_group!=null){
            $amount = $products_in_stock_group->qty;
        }
        if ($amount > 0) {
            return 1;
        } else {
            return 0;
        }

        // $stocks = [];
        // $stock_groups = StockGroup::where(['group_id' => $stock_group_id])->get();
        // foreach ($stock_groups as $item) {
        //     $stocks[] = $item->stock_id;
        // }
        // $amount = 0;
        // $product_in_stock = ProductsInStock::whereIn('stock_id', $stocks)->where(['product_id' => $this->id])->get();
        // foreach ($product_in_stock as $item) {
        //     $amount = $amount + $item->amount;
        // }
        // if ($amount > 0) {
        //     return 1;
        // } else {
        //     return 0;
        // }
    }

    public function Certificates()
    {
        return $this->hasMany(Certificate::class, 'product_id', 'id');
    }

    /**
     * @return string
     */
    public function getBrandNameAttribute(): string
    {
        $brand = Brand::where(['id' => $this->brand_id])->first();

        if ($brand != null) {
            return $brand->name;
        }
        return '';
    }

    public function getMainPictureAttribute()
    {
        $path = 'public/img/products/' . $this->id . '/';
        $files = Storage::files($path);
        if (count($files)>0){
            return $files[0];
        }
        else {
            return '';
        }

        $picture = Storage::url('public/img/products/' . $this->id . '/1.jpeg');
        return $picture;
    }

    public function getCertificatesFilesAttribute()
    {
        $files = [];
        foreach ($this->certificates as $item) {
            $files[] = Storage::url('public/img/certificates/' . $item->file_name . '');
        }
        return $files;
    }

    public function getPicturesAttribute()
    {
        $path = 'public/img/products/' . $this->id . '/';
        $files = Storage::files($path);
        return $files;
    }

    public function getCountryOfConsignmentNameAttribute()
    {
        $country = Country::where(['id' => $this->country_of_consignment_id])->first();

        if ($country != null) {
            $locale = App::getLocale();
            if ($locale == 'ru') {
                return $country->name_ru;
            } else {
                return $country->name;
            }
        }
        return '';
    }

    public function getCountryOfBrandRegistrationNameAttribute()
    {
        $country = Country::where(['id' => $this->country_of_brand_registration_id])->first();

        if ($country != null) {
            $locale = App::getLocale();
            if ($locale == 'ru') {
                return $country->name_ru;
            } else {
                return $country->name;
            }
        }
        return '';
    }



    public function getMyAmountAttribute()
    {
        $user = Auth::user();
        if ($user->parent_id!=null){
            $parent_user = User::where(['id'=>$user->parent_id])->first();
            $stock_group_id = $parent_user->stock_group_id;
        }else{
            $stock_group_id = $user->stock_group_id;
        }
        $product_in_stock = ProductsInStockGroup::where(['stock_group_id' => $stock_group_id])
            ->where(['product_id' => $this->id])->get();
        $amount = 0;
        foreach ($product_in_stock as $item) {
            $amount = $amount + $item->qty;
        }
        if ($this->display_balances < $amount) {
            $amount = '>' . number_format($this->display_balances, 0, '.', '');
        }
        return $amount;
    }

    public function getCenterAmountAttribute()
    {


        $stock_group_id = Setting::where(['key' => 'central_stock_group'])->first()->value;
        $product_in_stock = ProductsInStockGroup::where(['stock_group_id' => $stock_group_id])
            ->where(['product_id' => $this->id])->get();
        $amount = 0;
        foreach ($product_in_stock as $item) {
            $amount = $amount + $item->qty;
        }
        if ($this->display_balances < $amount) {
            $amount = '>' . number_format($this->display_balances, 0, '.', '');
        }
        return $amount;
    }

    public function getMyWishlistAttribute()
    {
        $wihlist = Wishlist::where([
            'user_id' => Auth::user()->id,
            'product_id' => $this->id
        ])->first();
        //return 22;
        if ($wihlist != null) {
            return true;
        } else {
            return false;
        }
    }

    public function getInCartAttribute()
    {
        $cart = Cart::where([
            'user_id' => Auth::user()->id,
            'product_id' => $this->id
        ])->first();

        if ($cart != null) {
            return true;
        } else {
            return false;
        }
    }

    public function getCategoryNameAttribute()
    {
        $category = Category::where(['id' => $this->category_id])->first();

        if ($category != null) {
            return $category->name;
        }
        return '';
    }



    public function getPersonalPriceUahAttribute()
    {
        $user = Auth::user();

        $row_price_type_id = IndividualPriceType::where(['customer_id' => $user->user_code, 'product_id' => $this->id])->first();
        $price_type_id = null;
        if ($row_price_type_id != null) {
            $price_type_id = $row_price_type_id->price_type_id;
        }
        if ($price_type_id == null) {
            $price_type_id = UserSettings::where(['customer_id' => $user->customer_code])->first()->price_type_id;
        }
        $product_price = ProductPrice::where(['product_id' => $this->id, 'price_type_id' => $price_type_id])->first();
        if ($product_price == null) {
            return '0';
        }

        $price = 0;
        if ($product_price != null) {
            $price = $product_price->price;
        }
        $second_currency = Currency::where(['id' => 980])->first();
        $price = CurrencyController::Convert($price, Currency::where(['id' => $product_price->currency_id])->first(), $second_currency);

        return $price;
    }

    public function getPersonalPriceAttribute()
    {
        $user = Auth::user();

        $row_price_type_id = IndividualPriceType::where(['customer_id' => $user->user_code, 'product_id' => $this->id])->first();
        $price_type_id = null;
        if ($row_price_type_id != null) {
            $price_type_id = $row_price_type_id->price_type_id;
        }
        if ($price_type_id == null) {
            $price_type_id = UserSettings::where(['customer_id' => $user->customer_code])->first()->price_type_id;
        }
        $product_price = ProductPrice::where(['product_id' => $this->id, 'price_type_id' => $price_type_id])->first();

        $price = 0;
        if ($product_price != null) {
            $price = $product_price->price;
        }

        return $price;
    }

    public function getPersonalPriceCurrencyIdAttribute()
    {
        $user = Auth::user();

        if ($user->parent_id!=null){
            $parent_user = User::where(['id'=>$user->parent_id])->first();
            $customer_code = $parent_user->customer_code;
        }else{
            $customer_code = $user->customer_code;
        }

        $price_type_id = UserSettings::where(['customer_id' => $customer_code])->first()->price_type_id;
        $product_price = ProductPrice::where(['product_id' => $this->id, 'price_type_id' => $price_type_id])->first();

        $currency_id = 0;
        if ($product_price != null) {
            $currency_id = $product_price->currency_id;
        }

        return $currency_id;
    }

    public function getPersonalPriceCurrencyAttribute()
    {
        $user = Auth::user();

        if ($user->parent_id!=null){
            $parent_user = User::where(['id'=>$user->parent_id])->first();
            $customer_code = $parent_user->customer_code;
        }else{
            $customer_code = $user->customer_code;
        }

        $price_type_id = UserSettings::where(['customer_id' => $customer_code])->first()->price_type_id;
        $product_price = ProductPrice::where(['product_id' => $this->id, 'price_type_id' => $price_type_id])->first();

        $currency_id = 0;
        if ($product_price != null) {
            $currency_id = $product_price->currency_id;
        }


        $currency = Currency::where(['id' => $currency_id])->first();

        if ($currency == null) {
            return 0;
        }
        $code = $currency->code;

        if ($code == 'грн') {
            $code = 'UAH';
        }


        return $code;
    }


    public function getRetailPriceUahAttribute()
    {
        $user = Auth::user();

        $price_type_id = 2;
        $product_price = ProductPrice::where(['product_id' => $this->id, 'price_type_id' => $price_type_id])->first();

        $price = 0;
        if ($product_price != null) {
            $price = $product_price->price;
        } else {
            return $price;
        }

        $secont_currency = Currency::where(['id' => 980])->first();
        $price = CurrencyController::Convert($price, Currency::where(['id' => $product_price->currency_id])->first(), $secont_currency);


        return $price;
    }

    public function getStatusColorAttribute()
    {
        $status_id = $this->statusID($this->status_code);
        switch ($status_id) {
            case 1:
                $color_class = '';
                break;
            case 2:
                $color_class = 'danger';
                break;
            case 3:
                $color_class = 'primary';
                break;
            case 4:
                $color_class = 'success';
                break;
            case 5:
                $color_class = 'danger';
                break;
            case 6:
                $color_class = 'danger';
                break;
            case 7:
                $color_class = 'danger';
                break;
            case 8:
                $color_class = 'danger';
                break;
            case 9:
                $color_class = 'danger';
                break;

            default:
                $color_class = '';
                break;
        }

        return $color_class;
    }

    public function getStatusIdAttribute()
    {
        return $this->statusID($this->status_code);
    }

    public static function statusID($status)
    {
        $status_id = 0;
        if ($status == 'РабочийАсортимент') {
            $status_id = 1;
        } elseif ($status == 'Новинка') {
            $status_id = 2;
        } elseif ($status == 'Распродажа') {
            $status_id = 3;
        } elseif ($status == 'ПодЗаказ') {
            $status_id = 4;
        } elseif ($status == 'Производство') {
            $status_id = 5;
        } elseif ($status == 'СнятСПродажи') {
            $status_id = 6;
        } elseif ($status == 'Услуга') {
            $status_id = 7;
        } elseif ($status == 'Реклама') {
            $status_id = 8;
        } elseif ($status == 'Сервис') {
            $status_id = 9;
        };

        return $status_id;
    }

    public function getStatusNameAttribute()
    {
        $status_name = '';
        if (Session::get('locale') != 'ru') {
            $status_name = $this->status;
        } else {
            $status_name = $this->status_ru;
        }
        return $status_name;
    }
}
