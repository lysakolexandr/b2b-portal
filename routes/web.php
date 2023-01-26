<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\ApiTokenController;
use App\Http\Controllers\DraftController;
use App\Http\Controllers\CustomController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserSettingsController;
use App\Http\Controllers\PriceController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WishlistController;
use App\Models\Price;
use App\Models\Product;
use App\Models\ProductInPricelist;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get(
    '/',
    [OrderController::class, 'index']

    // test_function () {
    //     return view('welcome');
    // }

)->middleware(['auth'])->name('home');

Route::post('/send-password', [CustomController::class, 'sendPassword'])->name('send-password');

Route::get('/catalog/{id?}', [ProductController::class, 'products'])->name('catalog')->middleware(['auth']);
Route::get('/product/list', [ProductController::class, 'list'])->name('list')->middleware(['auth']);

Route::get('/pagination', array('as' => 'ajax.pagination', 'uses' => 'ProductsController@getProducts'))->middleware(['auth']);

Route::get('locale/{locale?}', [UserSettingsController::class, 'setLocale'])->name('locale')->middleware(['auth']);


Route::get('/product/{id?}', [ProductController::class, 'product'])->name('product')->middleware(['auth']);

Route::get('/category/{id?}', [CategoryController::class, 'index'])->name('category')->middleware(['auth']);

Route::get('/cart', [CartController::class, 'index'])->middleware(['auth'])->name('cart');

Route::get('/cart/add', [CartController::class, 'add'])->name('add')->middleware(['auth']);
Route::get('/cart/set', [CartController::class, 'set'])->name('set')->middleware(['auth']);

Route::get('/clear-cart', [CartController::class, 'clear'])->name('clear-cart')->middleware(['auth']);

Route::get('/delivery', [ProductController::class, 'delivery'])->middleware(['auth']);

Route::get('/drafts', [DraftController::class, 'index'])->middleware(['auth']);

Route::get('/draft-edit/{id?}', [DraftController::class, 'DraftEdit'])->middleware(['auth']);
Route::get('/draft/add', [App\Http\Controllers\DraftController::class, 'add'])->name('add')->middleware(['auth']);
Route::get('/draft/copy/{id?}', [DraftController::class, 'DraftCopy'])->middleware(['auth']);
Route::get('/draft/delete/{id?}', [DraftController::class, 'DraftDelete'])->middleware(['auth']);

Route::get('/order-list', [OrderController::class, 'index'])->middleware(['auth']);

Route::get('/prices-xml', [PriceController::class, 'xml'])->middleware(['auth']);
Route::get('/prices-xls', [PriceController::class, 'xls'])->middleware(['auth']);
Route::get('/prices/save-name', [PriceController::class, 'saveName'])->middleware(['auth']);
Route::get('/prices/save', [PriceController::class, 'save'])->middleware(['auth']);
Route::get('/prices/delete', [PriceController::class, 'delete'])->middleware(['auth']);

Route::get('/prices-xml-edit', [PriceController::class, 'xmlEdit'])->middleware(['auth']);
Route::get('/prices-xls-edit', [PriceController::class, 'xlsEdit'])->middleware(['auth']);
Route::get('smart-search', [ProductController::class, 'smartSearch'])->name('smart-search');

Route::get('/tree-category', [PriceController::class, 'treeCategory'])->middleware(['auth']);
Route::get('/price/check-category', [PriceController::class, 'checkCategory'])->middleware(['auth']);

//Route::get('/price/download/{id?}/{lang?}/{prom?}/{all?}/{type?}', [PriceController::class, 'download'])->name('price-download');

Route::post('cart/make-order', [CartController::class, 'MakeOrder'])->middleware(['auth'])->name('make-order');

Route::get('cart/make-order', [OrderController::class, 'index'])->middleware(['auth']);

Route::get('/wishlist', [WishlistController::class, 'index'])->middleware(['auth']);
Route::get('/wishlist/add', [WishlistController::class, 'add'])->name('add')->middleware(['auth']);

Route::get('/wishlist/delete', [WishlistController::class, 'delete'])->name('delete')->middleware(['auth']);

Route::get('/filter', [ProductController::class, 'filter'])->name('filter')->middleware(['auth']);

Route::get('/finish', [CartController::class, 'finish'])->name('finish')->middleware(['auth']);

Route::get('set-hide-price',[ProfileController::class,'setHidePrice'])->name('setHidePrice')->middleware(['auth']);

Route::get('/profile', [ProfileController::class, 'index'])->name('index')->middleware(['auth']);

Route::get('/balance', [ProfileController::class, 'balance'])->name('balance')->middleware(['auth']);

Route::get('/profile/delete', [ProfileController::class, 'deleteTrustedUser'])->name('delete')->middleware(['auth']);

Route::get('/user-add', [ProfileController::class, 'addTrustedUser'])->name('user-add')->middleware(['auth']);
Route::get('/user-edit/{id?}', [ProfileController::class, 'editTrustedUser'])->name('user-edit')->middleware(['auth']);
Route::post('/user-save', [ProfileController::class, 'saveTrustedUser'])->name('user-save')->middleware(['auth']);
Route::post('/changePassword', [ProfileController::class, 'changePassword'])->name('changePassword')->middleware(['auth']);

Route::get('/api-settings', [ApiTokenController::class, 'apiSettings'])->name('api-settings')->middleware(['auth']);
Route::get('/api-manual', [ApiTokenController::class, 'apiManual'])->name('api-manual')->middleware(['auth']);
Route::get('/change-token', [ApiTokenController::class, 'update'])->name('change-token')->middleware(['auth']);

Route::get('/order-detail/{id?}', [OrderController::class, 'orderDetail'])->name('order-detail')->middleware(['auth']);

Route::get('/get-token/{email?}/{pass?}', [ApiTokenController::class, 'getToken'])->name('get-token');

Route::post('add_pictures', [ProductController::class, 'addPictures']);

Route::post('/add_catalog_pictures', [CategoryController::class, 'addPictures']);

Route::post('add_certificates', [ProductController::class, 'addCertificates']);

Route::get('/order-repeat/{id?}', [OrderController::class, 'orderRepeat'])->name('order-repeat')->middleware(['auth']);

Route::get('reconciliation-act',[ProfileController::class,'getAct'])->middleware(['auth']);
Route::get('print-order/{id?}',[OrderController::class,'printOrder'])->name('print-order')->middleware(['auth']);
Route::get('print-retail-order/{id?}',[OrderController::class,'printRetailOrder'])->name('print-retail-order')->middleware(['auth']);
Route::get('print-invoice/{id?}',[OrderController::class,'printInvoice'])->name('print-invoice')->middleware(['auth']);

Route::get('make-order-from-excel',[OrderController::class,'makeOrderFromExcel'])->name('make-order-from-excel')->middleware(['auth']);

Route::post('upload_order',[OrderController::class,'uploadOrder'])->middleware(['auth'])->name('upload-order');

Route::get('/price/{id?}/{lang?}/{all?}/price.xlsx', function ($id, $lang, $all) {
    if ($all == 1) {
        Auth::loginUsingId($id);
        $price_model = Price::where(['all'=>1,'type'=>'xls'])->first();
        $price_id = $price_model->id;
    } else {
        $price_model = Price::where(['id' => $id])->first();
        Auth::loginUsingId($price_model->user_id);
        $price_id = $id;
    };
    return PriceController::downloadXls($price_id,$lang,$all);
})->name('download-xls');

Route::get('/price-test/{id?}/{lang?}/{all?}/price.xlsx', function ($id, $lang, $all) {
    if ($all == 1) {
        Auth::loginUsingId($id);
        $price_model = Price::where(['all'=>1,'type'=>'xls'])->first();
        $price_id = $price_model->id;
    } else {
        $price_model = Price::where(['id' => $id])->first();
        Auth::loginUsingId($price_model->user_id);
        $price_id = $id;
    };
    return PriceController::downloadXlsTest($price_id,$lang,$all);
})->name('download-xls-test');
Route::get('/price/{id?}/{lang?}/{prom?}/{all?}/{type?}/export.xml', function ($id, $lang, $prom, $all, $type) {
    // PriceController::downloadXls($id,$lang,$prom,$all);

    if ($all == 1) {
        Auth::loginUsingId($id);
        $price_model = Price::where(['all'=>1,'type'=>'xml'])->first();
        $price_id = $price_model->id;
    } else {
        $price_model = Price::where(['id' => $id])->first();
        Auth::loginUsingId($price_model->user_id);
        $price_id = $id;
    }
    $stock_group_id = Auth::user()->stock_group_id;
    if ($type=='xml'){
        $categories = PriceController::arrayOfCategories($id,$lang,$all);
        //dd($lang);
        return response()->view('prices.xml', ['categories_xml'=>$categories,'id'=>$price_id,'lang'=>$lang,'prom'=>$prom,'stock_group_id'=>$stock_group_id])->header('Content-type', 'text/xml');
    }elseif($type=='xls'){
        return PriceController::downloadXls($price_id,$lang,$all);
    }else{
        return '';
    }

})->name('price-download');

Route::get('/price-generate', function () {
    $result = PriceController::generatePrice();
    return $result;
})->name('price-generate');

Route::get('/shedule', function () {
    $prices = Price::where(['created' => 0])->get();
            foreach ($prices as $item) {

                $j = PriceController::createProductInPriceList($item->id, 0, 'xml');
                $item->created = 1;
                $item->save();

            }
})->name('shedule');

Route::get('/new-price-generate', function(){
    $prices = Price::where(['created' => 0, 'all' => 0,'temp'=>0])->get();
            foreach ($prices as $item) {
                PriceController::createProductInPriceList($item->id, 0);
                $item->created = 1;
                $item->save();
            }
});

Route::get('/finish', function(){
    $showFilter = false;
    return view('catalog.finish', compact('showFilter'));
});


