<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

class Category extends Model
{
    use HasFactory;

    public function getChildrenAttribute()
    {
        //dd($this->id);
        $children = Category::orderBy('sort', 'asc')->where(['parent_id' => $this->id])->get();
        return $children;
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

    public function getNameUaAttribute()
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
