<?php

namespace App\Http\Middleware;

use App\Models\UserSettings;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class Localization
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();
        if ($user != null) {
            $record = UserSettings::where(['customer_id' => $user->customer_code])->first();
            if ($record != null) {
                App::setLocale(Session::get('locale', $record->language));
            } else {
                App::setLocale(Session::get('locale', 'ua'));
            }
        } else {
            App::setLocale(Session::get('locale', 'ua'));
        }
        //}
        return $next($request);
    }
}
