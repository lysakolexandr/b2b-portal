<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    use HasFactory;

    public static function getUSD(){
        $rate = Currency::where(['id' => 840])->first()->rate;
        return $rate;
    }
    public static function getEUR(){
        $rate = Currency::where(['id' => 978])->first()->rate;
        return $rate;
    }

    public static function getEUR_USD(){
        $rateEUR = Currency::where(['id' => 978])->first()->cross_rate;
        $rateUSD = Currency::where(['id' => 840])->first()->cross_rate;
        return ''.round($rateEUR,2).'/'.round($rateUSD,2);
    }
}
