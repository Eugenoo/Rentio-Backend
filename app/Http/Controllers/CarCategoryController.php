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

    public function store(StoreCarCategoryRequest $request)
    {
        $base64_string = $request->photo;

        $data = base64_decode($base64_string);
        $fileName = $request->name."_photo".".png";

        Storage::disk('local')->put($fileName, $data);

        //Save to database

        $data = $request->validated();

        $data['photo'] = storage_path('app/private/').$fileName;

        $carCategory = CarCategory::make($data);

        return $carCategory;
    }

    public function show(CarCategory $category)
    {
        $carcategory = CarCategory::get($category);
        return $carcategory;
    }

    public function update(UpdateCarCategoryRequest $request)
    {
        $carCategory = CarCategory::findOrFail($request->id);
        $carCategory->edit($request->validated());
    }

    public function delete(Request $request)
    {
        CarCategory::remove();
        return $request;
    }
}
