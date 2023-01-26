<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    use HasFactory;

    public function getContractNameAttribute()
    {
        if ($this->type==0) {
            $name = 'cash';
        } else {
            $name = 'no_cash';
        }


        return $name;
    }
}
