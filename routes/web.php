<?php

use App\Http\Controllers\UserController;
use App\Http\Middleware\SessionAuthenticate;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return inertia('Home');
});
Route::get('/login',[UserController::class, 'loginPage'])->name('login');
Route::get('/logout', [UserController::class, 'userLogout'])->name('logout');
Route::get('/registration',[UserController::class, 'registrationPage'])->name('registration');

Route::post('/user-registration', [UserController::class, 'userRegistration'])->name('user.registration');
Route::post('/user-login', [UserController::class, 'userLogin'])->name('user.login');

Route::middleware(SessionAuthenticate::class)->group(function () {
    Route::get('/dashboard', [UserController::class, 'dashboardPage'])->name('dashboard');
    
});
