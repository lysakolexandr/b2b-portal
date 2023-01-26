<?php

namespace App\Providers;

use App\Http\Controllers\CartController;
use App\Http\Controllers\WishlistController;
use App\Models\Cart;
use App\Models\Category;
use App\Models\Currency;
use App\Models\UserSettings;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

        Schema::defaultStringLength(200);
        view()->composer('*', function ($view) {



            $view->with('USD_rate', Currency::getUSD())
            ->with('EUR_rate', Currency::getEUR())
            ->with('EUR_USD_rate', Currency::getEUR_USD())
            ->with('categories',$this::main_categories())
            ->with('cartCount',CartController::getCartCount())
            ->with('wishlistCount',WishlistController::getWishlistCount())
            ->with('hidePrice',UserSettings::hidePrice())
            ->with('blockHidePrice',UserSettings::blockHidePrice())
            ->with('available',UserSettings::available())
            ->with('sort',UserSettings::sort());
        });
    }

    private static function main_categories()
    {
        return Category::orderBy('sort', 'asc')->where(['parent_id' => 0])->get();
    }
}
