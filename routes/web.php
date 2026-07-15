<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/reset-password/{token}', function ($token) {
    $email = request('email');

    return redirect(
        config('app.frontend_url') .
        "/reset-password/$token?email=" .
        urlencode($email)
    ); // albo redirect do frontendu
})->name('password.reset');
