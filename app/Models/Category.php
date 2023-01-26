<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class Category extends Model
{
    use HasFactory;

    public function getChildrenAttribute()
    {
        //dd($this->id);
        $children = Category::orderBy('sort', 'asc')->where(['parent_id' => $this->id])->get();
        return $children;
    }

    public function getPictureAttribute()
    {
        $picture = "https://".setting('domain')."/img/logo.png";

        $picture_exists = Storage::disk('public')->exists( '/img/categories/'.$this->id.'.jpeg' );
        if ($picture_exists){
            $picture = Storage::url('public/img/categories/' . $this->id . '.jpeg');
        }
        return $picture;
    }

    public function getNameAttribute()
    {
        $locale = App::getLocale();
        if ($locale == 'ru') {
            $object = DB::table($this->table)->where('id', $this->id)->first();
            $name = $object->name_ru;
        } else {
            $object = DB::table($this->table)->where('id', $this->id)->first();
            $name = $object->name;
        }

        return $name;
    }

    public function getNameuaAttribute()
    {

        $object = DB::table($this->table)->where('id', $this->id)->first();
        $name = $object->name;


        return $name;
    }

    public function getNameRuAttribute()
    {

        $object = DB::table($this->table)->where('id', $this->id)->first();
        $name = $object->name_ru;


        return $name;
    }
}
