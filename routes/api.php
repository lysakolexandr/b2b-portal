<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiTokenController;
use App\Http\Controllers\ApiExchangeController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/currency', [ApiTokenController::class, 'currency'])->middleware(['auth:api'])->name('currency');
Route::get('/categories', [ApiTokenController::class, 'categories'])->middleware(['auth:api'])->name('categories');
Route::get('/products/available', [ApiTokenController::class, 'available'])->middleware(['auth:api'])->name('available');
Route::get('/products', [ApiTokenController::class, 'products2'])->middleware(['auth:api'])->name('products');
Route::get('/orders', [ApiTokenController::class, 'orders'])->middleware(['auth:api'])->name('orders');


//Exchange with 1C
Route::post('/delivery_points', [ApiExchangeController::class, 'store'])->middleware(['auth:api']);
Route::delete('/delivery_points', [ApiExchangeController::class, 'delete'])->middleware(['auth:api']);
Route::put('/delivery_points', [ApiExchangeController::class, 'update'])->middleware(['auth:api']);

