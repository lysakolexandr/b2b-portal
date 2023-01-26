<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSettings extends Model
{
    use HasFactory;

    public static function getLocale()
    {
        $user = auth()->user();
        $record = UserSettings::where(['customer_id' => $user->customer_code])->first();
        if ($record!=null){
            $locale = $record->language;
        }else{
            $locale = 'ua';
        }
        return $locale;
    }

    public static function hidePrice()
    {
        $user = auth()->user();
        if ($user==null) {
            return 0;
        }

        if ($user->parent_id!=null){
            if ($user->price_view==1){
                return $user->hide_price;
            }else{
                return 1;
            }
        }else{
            return $user->hide_price;
        }

        // $record = UserSettings::where(['customer_id' => $user->customer_code])->first();
        // if ($record!=null){
        // $hide = $record->hide_price;
        // $hide_price = ($hide==1);

        // if ($user->parent_id!=null){
        //     if ($user->price_view==0){
        //         $hide_price = 1;
        //     }else{
        //         $hide_price=0;
        //     }
        // }}else{
        //     $hide_price = 0;
        // }

        // return $hide_price;
    }

    public static function available()
    {
        $user = auth()->user();
        if ($user==null) {
            return 'false';
        }
        $record = UserSettings::where(['customer_id' => $user->customer_code])->first();
        if ($record!=null){
            $available = $record->available;
        }else{
            $available = 'false';
        }


        return $available;
    }

    public static function blockHidePrice()
    {
        $user = auth()->user();
        if ($user==null) {
            return 0;
        }
        $hide_price = 0;
        if ($user->parent_id!=null){
            if ($user->price_view==1){
                $hide_price = 0;
            }else{
                $hide_price = 1;
            }
        }

        return $hide_price;
    }

    public static function sort()
    {
        $user = auth()->user();
        if ($user==null) {
            return 0;
        }
        $record = UserSettings::where(['customer_id' => $user->customer_code])->first();
        if ($record != null){
        return $record->sort;
        }else{
            return 0;
        }
    }
}
