<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    public function getSettingsAttribute()
    {
        $settings = UserSettings::orderBy('id','desc')->where(['customer_id'=>$this->customer_code])->first();
        return $settings;
    }

    public function getCurrentContractAttribute()
    {
        $user = auth()->user();
        //dd($user->selected_contract_id);
        $contract = Contract::orderBy('id','desc')->where(['id'=>$user->selected_contract_id])->first();
        // if ($contract==null) {
        //     $contract = Contract::orderBy('id','desc')->where(['client_id'=>$this->customer_code,'type'=>0])->first();
        // }
        return $contract;
    }

    public function getStockGroupIdAttribute(){
        $settings = UserSettings::orderBy('id','desc')->where(['customer_id'=>$this->customer_code])->first();
        $result = 0;
        if ($settings!=null){
            $result = $settings->stock_group_id;
        }
        return $result;
    }

    public function getShowPriceAttribute(){
        if ($this->parent_id==null){
            return true;
        }else{
            if ($this->price_view==1){
                return true;
            }else{
                return false;
            }
        }

    }

    public function getShowActAttribute(){
        if ($this->parent_id==null){
            return true;
        }else{
            if ($this->settlements_view==1){
                return true;
            }else{
                return false;
            }
        }

    }

    public function getContractsAttribute()
    {
        $contracts = Contract::orderBy('id','desc')->where(['client_id'=>$this->customer_code])->get();
        return $contracts;
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
