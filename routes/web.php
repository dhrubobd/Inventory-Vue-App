<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ProductController;
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
Route::get('/send-otp',[UserController::class, 'sendOTPPage'])->name('sendotp.page');
Route::post('/send-otp', [UserController::class, 'sendOTPCode'])->name('SendOTPCode');
Route::get('/verify-otp',[UserController::class, 'verifyOTPPage'])->name('VerifyOTPPage');
Route::post('/verify-otp', [UserController::class, 'verifyOTP'])->name('VerifyOTP');
Route::get('/reset-password',[UserController::class, 'resetPasswordPage']);
Route::post('/reset-password', [UserController::class, 'resetPassword']);

Route::middleware(SessionAuthenticate::class)->group(function () {
    Route::get('/dashboard', [UserController::class, 'dashboardPage'])->name('dashboard');
    Route::get('/ProfilePage', [UserController::class, 'profilePage']);
    Route::post('/user-update', [UserController::class, 'userUpdate']);

    //Category
    Route::get('/CategoryPage', [CategoryController::class, 'categoryPage'])->name('CategoryPage');
    Route::get('/CategorySavePage', [CategoryController::class, 'categorySavePage'])->name('CategorySavePage');
    Route::post('/create-category', [CategoryController::class, 'createCategory'])->name('category.create');
    Route::post('/update-category', [CategoryController::class, 'categoryUpdate'])->name('category.update');
    Route::get('/delete-category/{id}', [CategoryController::class, 'categoryDelete'])->name('category.delete');

    //Product
    Route::get('/ProductPage', [ProductController::class, 'productPage'])->name('product.page');
    Route::get('/ProductSavePage', [ProductController::class, 'productSavePage'])->name('ProductSavePage');
    Route::post('/create-product', [ProductController::class, 'createProduct'])->name('CreateProduct');
    Route::post('/update-product', [ProductController::class, 'productUpdate'])->name('ProductUpdate');
    Route::get('/delete-product/{id}', [ProductController::class, 'productDelete'])->name('ProductDelete');

    //Customer
    Route::get('/CustomerPage', [CustomerController::class, 'customerPage'])->name('CustomerPage');
    Route::get('/CustomerSavePage', [CustomerController::class, 'customerSavePage'])->name('CustomerSavePage');
    Route::post('/create-customer', [CustomerController::class, 'createCustomer'])->name('CreateCustomer');
    Route::post('/update-customer', [CustomerController::class, 'customerUpdate'])->name('CustomerUpdate');
    Route::get('/delete-customer/{id}', [CustomerController::class, 'customerDelete'])->name('CustomerDelete');
    Route::get('/list-customer', [CustomerController::class, 'customerList'])->name('CustomerList');
    Route::post('/customer-by-id', [CustomerController::class, 'customerById'])->name('CustomerById');
});
