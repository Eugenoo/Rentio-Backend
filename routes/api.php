<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CarController;
use App\Http\Controllers\CarCategoryController;
use App\Http\Controllers\ReservationController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::get('/test', function (Request $request) {
    return response()->json(['message' => 'API is working']);
});

//Car

Route::get('/car', [CarController::class, 'index']);
Route::get('car/{id}', [CarController::class, 'show']);
Route::post('/car', [CarController::class, 'store']);
Route::put('/car', [CarController::class, 'update']);
Route::delete('/car', [CarController::class, 'delete']);


//CarController
Route::get('carcategory', [CarCategoryController::class, 'index']);
Route::get('carcategory/{id}', [CarCategoryController::class, 'show']);
Route::post('carcategory', [CarCategoryController::class, 'store']);
Route::put('carcategory', [CarCategoryController::class, 'update']);
Route::delete('carcategory', [CarCategoryController::class, 'delete']);

//Route::resource('cars', CarController::class);
//Reservation
Route::get('reservation',[ReservationController::class, 'index']);
Route::get('reservation/{id}',[ReservationController::class, 'show']);
