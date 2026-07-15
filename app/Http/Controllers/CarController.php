<?php

namespace App\Http\Controllers;

use App\Helpers\FileHelper;
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
        $cars = Car::with('category')->get();
        return response()->json($cars);
    }

    public function show($param)
    {
        //if numeric
        if(is_numeric($param))
        {
            $car = Car::findOrFail($param);
        }
        else {
            //find by slug
            $car = Car::getBySlug($param);
        }

        return response()->json($car);
        //if string
        //return model that fit string
    }

    public function store(StoreCarRequest $request)
    {
        $data = $request->validated();
        $baseUrl = getenv('APP_URL');

        //convert car model to kebabcase to work with filesystem
        $fullCarName = $request->brand." ".$request->model;
        $carName = str_replace(" ", "_", $fullCarName);

        // Obsługa pliku zdjęcia
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            //convert car model to kebabcase to work with filesystem
            $fileName = $carName."_photo".".png";
            Storage::disk(env('FILESYSTEM_DISK'))->putFileAs('', $file, $fileName);
            // zapis ścieżki do bazy
            $data['photo'] = $baseUrl . "/storage/".$fileName;
        }
        $data['slug'] = $carName;

        //Record in DB
        $car = Car::make($data);
        return response()->json(['car' => $car]);
    }

    public function update(UpdateCarRequest $request)
    {
        $car = Car::findOrFail($request->id);
        $car->edit($request->validated());

        return response()
            ->json([
                'message' => 'Car edited successfully',
                'car' => $car,
            ], 200);
    }

    public function delete(Request $request)
    {
        $car = Car::find($request->id);
        $car->delete();
    }

    public function getReservations(Request $request)
    {

    }

    public function updatePhoto(Request $request, $id)
    {
        $data = $request->validate([
            "photo" => "nullable|image|mimes:jpeg,jpg,png,webp|max:2048"
        ]);

        $car = Car::findOrFail($id);

        // Obsługa pliku zdjęcia
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            //convert car model to kebabcase to work with filesystem
            $fullCarName = $car->brand." ".$car->model;
            $baseName = str_replace(" ", "_", $fullCarName).'_photo';

            $ext = $file->getClientOriginalExtension();

            $fileName = FileHelper::getUniqueFilename($baseName.'.'.$ext);

            //delete old photo?
            $baseUrl = getenv('APP_URL');

            Storage::disk(env('FILESYSTEM_DISK'))->putFileAs('', $file, $fileName);
            // zapis ścieżki do bazy
            $data['photo'] = $baseUrl."/storage/".$fileName;
        }

        $car->update($data);

        return response()->json([
            'car' => $car,
            'photo_url' => $car->photo ? asset('storage/'.$car->photo) : null
        ]);
    }
}

