<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class MainModel extends Model
{
    use HasFactory;

   public function getNameAttribute()
   {
       $locale = App::getLocale();
       if ($locale=='ru'){
           $object = DB::table($this->table)->where('id', $this->id)->first();
           $name = $object->name_ru;
       }else{
           $object = DB::table($this->table)->where('id', $this->id)->first();
           $name = $object->name;
       }

       return $name;
   }
}
