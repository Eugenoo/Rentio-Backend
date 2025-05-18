<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    protected $fillable = [
        'id',
        'brand',
        'model',
        'registration_number',
        'year',
        'available',
        'car_category_id',
        'price_per_day',
        'status',
        'photo',
    ];

    public function show()
    {
        return "test";
    }

    public static function make($request)
    {
        $car = new self($request);
        $car->save();
        return $car;
    }
}
