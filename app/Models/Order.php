<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use NumberFormatter;

class Order extends Model
{
    use HasFactory;

    protected $visible = [
        'id',
        'code',
        'created_at',
        'sourceName','totalSum',
    ];
    protected $appends = ['sourceName','totalSum',];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getSourceNameAttribute(){
        $sourceName = '';
        if ($this->source==1){
            $sourceName = 'B2B';
        }elseif ($this->source==2) {
            $sourceName = __('l.office');
        }elseif ($this->source==3) {
            $sourceName = __('l.manager');
        }
        return $sourceName;
    }

    public function getSourceClassAttribute(){
        $sourceClass = '';
        if ($this->source==1){
            $sourceClass = 'bg-primary';
        }elseif ($this->source==2) {
            $sourceClass = 'bg-warning';
        }elseif ($this->source==3) {
            $sourceClass = 'bg-success';
        }
        return $sourceClass;
    }

    public function products()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getSumUahAttribute(){
        $rows = OrderItem::where(
            'order_id',$this->id
        )->get();
        $res = 0;
        foreach ($rows as $item) {
            if ($item->product->personalPriceCurrency=='UAH') {
                $res+=($item->price*$item->qty);
            }

        }
        $fmt = new NumberFormatter( 'en-CA', NumberFormatter::PATTERN_DECIMAL,"0.00");
        $res = $fmt->format($res);
        return $res;
    }

    public function getTotalSumAttribute(){
        $uah = 0;
        $rows = OrderItem::where(
            'order_id',$this->id
        )->get();
        foreach ($rows as $item) {
            if ($item->product->personalPriceCurrency=='UAH') {
                $uah+=($item->price*$item->qty);
            }elseif($item->product->personalPriceCurrency=='USD'){
                $uah+=($item->price*$item->qty*Currency::getUSD());
            }elseif($item->product->personalPriceCurrency=='EUR'){
                $uah+=($item->price*$item->qty*Currency::getEUR());
            };

        }
        $fmt = new NumberFormatter( 'en-CA', NumberFormatter::PATTERN_DECIMAL, "0.00");
        $uah = $fmt->format($uah);
        return $uah;

    }

    public function getSumUsdAttribute(){
        $rows = OrderItem::where(
            'order_id',$this->id
        )->get();
        $res = 0;
        foreach ($rows as $item) {
            if ($item->product->personalPriceCurrency=='USD') {
                $res+=($item->price*$item->qty);
            }

        }
        $fmt = new NumberFormatter( 'en-CA', NumberFormatter::PATTERN_DECIMAL, "0.00");
        $res = $fmt->format($res);
        return $res;
    }

    public function getSumEurAttribute(){
        $rows = OrderItem::where(
            'order_id',$this->id
        )->get();
        $res = 0;
        foreach ($rows as $item) {
            if ($item->product->personalPriceCurrency=='EUR') {
                $res+=($item->price*$item->qty);
            }

        }
        $fmt = new NumberFormatter( 'en-CA', NumberFormatter::PATTERN_DECIMAL, "0.00");
        $res = $fmt->format($res);
        return $res;
    }

}
