<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Car extends Model
{

    use SoftDeletes;

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
        'slug'
    ];

    public function show()
    {
        return "test";
    }

    public function edit($data)
    {
        $this->update($data);
        return $data;
    }

    public static function make($request)
    {
        $car = new self($request);
        $car->save();
        return $car;
    }

    public static function getBySlug($slug)
    {
        return Car::where('slug', $slug)->first();

    }
}
