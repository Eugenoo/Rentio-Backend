<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CarController;
use App\Http\Controllers\CarCategoryController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;

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
Route::post('carcategory', [CarCategoryController::class, 'store'])->middleware('auth:sanctum');
Route::put('carcategory', [CarCategoryController::class, 'update']);
Route::delete('carcategory', [CarCategoryController::class, 'delete']);

//Route::resource('cars', CarController::class);
//Reservation
Route::get('reservation',[ReservationController::class, 'index'])->middleware('auth:sanctum');
Route::get('reservation/{id}',[ReservationController::class, 'show'])->middleware('auth:sanctum');
Route::post('reservation',[ReservationController::class, 'create'])->middleware('auth:sanctum');
Route::put('reservation',[ReservationController::class, 'update'])->middleware('auth:sanctum');
Route::delete('reservation',[ReservationController::class, 'delete'])->middleware('auth:sanctum');


//User
Route::get('/user', [UserController::class, 'index'])->middleware('auth:sanctum');
Route::get('/user/{id}', [UserController::class, 'show'])->middleware('auth:sanctum');
Route::post('/user', [UserController::class, 'create'])->middleware('auth:sanctum');
Route::put('/user', [UserController::class, 'update'])->middleware('auth:sanctum');
Route::delete('/user', [UserController::class, 'delete'])->middleware('auth:sanctum');

//Auth
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);


