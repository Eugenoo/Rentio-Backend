<?php

namespace App\Http\Controllers;

use App\Http\Requests\CarRequest\StoreCarRequest;
use App\Models\CarCategory;
use Illuminate\Http\Request;
use App\Models\Car;
use Illuminate\Support\Facades\Storage;

class CarController extends Controller
{
    //
    public function index()
    {
        $cars = Car::all();
        return $cars;
    }

    public function show()
    {

    }

    public function store(StoreCarRequest $request)
    {
        $base64_string = $request->photo;
        $data = base64_decode($base64_string);
        //convert car model to kebabcase to work in filesystem
        $carName = str_replace(" ", "_", $request->model);
        $fileName = $carName."_photo".".png";
        Storage::disk('local')->put($fileName, $data);
        $data = $request->validated();

        $data['photo'] = storage_path('app/private/').$fileName;

        $car = Car::make($data);

        return $car;
    }

    public function update()
    {

    }

    public function delete()
    {

    }
}
