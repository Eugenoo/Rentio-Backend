<?php

namespace App\Http\Controllers;

use App\Http\Requests\CarRequest\StoreCarRequest;
use App\Http\Requests\CarRequest\UpdateCarRequest;
use App\Models\CarCategory;
use Illuminate\Http\Request;
use App\Models\Car;
use Illuminate\Support\Facades\Storage;
use function PHPUnit\Framework\isNumeric;

class CarController extends Controller
{
    //
    public function index()
    {
        $cars = Car::all();
        return $cars;
    }

    public function show($request)
    {
        //if numeric
        if(is_numeric($request))
        {
            $car = Car::findOrFail($request);
        }
        else {
            //find by slug
            $car = Car::getBySlug($request);
        }

        return $car;
        //if string
        //return model that fit string
    }

    public function showSlug($slug)
    {
        dd('test');
        dd($slug);
        $car = Car::findOrFail($request);
        return $car;
    }

    public function store(StoreCarRequest $request)
    {
        $base64_string = $request->photo;
        $data = base64_decode($base64_string);
        //convert car model to kebabcase to work in filesystem
        $fullCarName = $request->brand." ".$request->model;
        $carName = str_replace(" ", "_", $fullCarName);
        $fileName = $carName."_photo".".png";
        Storage::disk('public')->put($fileName, $data);
        $data = $request->validated();
        $data['photo'] = storage_path('app/private/').$fileName;

        $data['slug'] = $fullCarName;
        $car = Car::make($data);
        return $car;
    }

    public function update(UpdateCarRequest $request)
    {
        $car = Car::findOrFail($request->id);
        $car->edit($request->validated());
        return response('Data updated', 200)
            ->header('Content-Type', 'text/plain');
    }

    public function delete(Request $request)
    {
        $car = Car::find($request->id);
        $car->delete();
    }
}
