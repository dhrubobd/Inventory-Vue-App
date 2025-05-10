<?php

use App\Http\Middleware\SessionAuthenticate;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return inertia('Home');
});
Route::get('/login', function () {
    return inertia('Login');
});

Route::middleware(SessionAuthenticate::class)->group(function () {
    Route::get('/dashboard', function () {
        return inertia('Dashboard');
    });
});
