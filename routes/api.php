<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CarController;
use App\Http\Controllers\CarCategoryController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::get('/test', function (Request $request) {
    return response()->json(['message' => 'API is working']);
});

//Car

Route::get('/car', [CarController::class, 'index']);

//CarController
Route::get('carcategory', [CarCategoryController::class, 'index']);
Route::post('carcategory/create', [CarCategoryController::class, 'store']);
Route::put('carcategory', [CarCategoryController::class, 'update']);
Route::delete('carcategory', [CarCategoryController::class, 'delete']);
//Route::resource('cars', CarController::class);
