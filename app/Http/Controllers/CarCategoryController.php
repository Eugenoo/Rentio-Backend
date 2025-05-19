<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCarCategoryRequest;
use App\Http\Requests\UpdateCarCategoryRequest;
use App\Models\Car;
use App\Models\CarCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


class CarCategoryController extends Controller
{
    public function index()
    {
        $categories = CarCategory::all();
        return $categories;
    }

    public function store(Request $request)
    {
        $newRequest = $request->validate([
            "name" => "required",
            "description" => "nullable",
            "photo" => "nullable",]);

        //if photo null jump
        if($newRequest['photo'] !== null){
            $base64_string = $newRequest['photo'];

            $data = base64_decode($base64_string);
            $fileName = $newRequest['name']."_photo".".png";
            Storage::disk('local')->put($fileName, $data);
            $newRequest['photo'] = storage_path('app/private/').$fileName;
        }
        //Save to database

        $carCategory = CarCategory::make($newRequest);

        return $carCategory;
    }

    public function show($category)
    {
        $carcategory = CarCategory::findOrFail($category);
        return $carcategory;
    }

    public function update(UpdateCarCategoryRequest $request)
    {
        $carCategory = CarCategory::findOrFail($request->id);
        $carCategory->edit($request->validated());
        return response('Data updated', 200)
            ->header('Content-Type', 'text/plain');
    }

    public function delete(Request $request)
    {
        $carCategory = CarCategory::find($request->id);
        $carCategory->delete();
    }
}
