<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryPoint extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'id',
        'address',
        'sort',
        'vendor_id',
        'user_id',
        'code'
    ];
}
