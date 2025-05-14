<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    protected $fillable = [
        'id',
        'name',
        'brand',
        'model',
        'category_id',
        'price_per_day',
        'status'
    ];
}
