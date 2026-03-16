<?php

namespace App\Http\Controllers;

use App\Helpers\FileHelper;
use App\Http\Requests\CarCategoryRequest\StoreCarCategoryRequest;
use App\Http\Requests\CarCategoryRequest\UpdateCarCategoryRequest;
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
        $data = $request->validated();
        $baseUrl = getenv('APP_URL');

        // Obsługa pliku zdjęcia
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            //convert car model to kebabcase to work with filesystem
            $categoryName = $request->name;
            $categoryStr = str_replace(" ", "_", $categoryName);
            $fileName = $categoryStr."_photo".".png";
            Storage::disk('public')->putFileAs('/categories/', $file, $fileName);
            // zapis ścieżki do bazy
            $data['photo'] = $baseUrl . "/storage/categories/".$fileName;
        }

        //Record in DB
        $car = CarCategory::make($data);
        return response()->json(['carCategory' => $car]);
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

    public function updatePhoto(Request $request, $id)
    {
        $data = $request->validate([
            "photo" => "nullable|image|mimes:jpeg,jpg,png,webp|max:2048"
        ]);

        $category = CarCategory::findOrFail($id);

        // Obsługa pliku zdjęcia
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            //convert car model to kebabcase to work with filesystem
            $fullCarName = $category->name."_category_photo";
            $baseName = str_replace(" ", "_", $fullCarName).'_photo';

            $ext = $file->getClientOriginalExtension();

            $fileName = FileHelper::getUniqueFilename($baseName.'.'.$ext);

            //delete old photo?

            $baseUrl = getenv('APP_URL');

            Storage::disk('public')->putFileAs('', $file, $fileName);
            // zapis ścieżki do bazy
            $data['photo'] = $baseUrl."/storage/".$fileName;
        }

        $category->update($data);

        return response()->json([
            'category' => $category,
            'photo_url' => $category->photo ? asset('storage/'.$category->photo) : null
        ]);
    }

}
