<?php

use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\StorageController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [SearchController::class, 'index'])->name("home");
Route::view('/restaurant-sign-up', 'restaurant.sign_up')->name("restaurant-sign-up");
Route::view('/contact-us', 'contact-us')->name('contact');
Route::view('/about-us', 'about-us')->name('about-us');
Route::view('/about-pre-authorisation', 'about-pre-authorisation')->name('about-pre-auth');
Route::get('/restaurant/{restaurant}', [RestaurantController::class, 'redirectWithName']);
Route::get('/restaurant/{restaurant}/{name}', [RestaurantController::class, 'show'])->name("restaurant.show");
Route::get('/restaurant/email/verify/{restaurant}', [RestaurantController::class, 'verifyEmail'])->name("restaurant.verify_email");

Route::get('/storage/files/{file}', [StorageController::class, 'show'])
    ->where('file', '.*')
    ->name("storage.file");

Route::middleware(['auth:sanctum', 'verified', 'admin'])->group(function(){
    Route::get('restaurants/status/{status}', [RestaurantController::class, 'status'])->name('restaurant.status');
});

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::view('/bookings', 'bookings.index')->name("bookings");
    Route::view('/dashboard', 'dashboard')->name('dashboard');
    Route::view('/my-restaurants', 'restaurant.manage.index')->name("my-restaurants");
    Route::get('/my-restaurants/bookings', [RestaurantController::class, 'bookingsSelect'])->name("restaurant.bookings_select");
});

Route::middleware(['auth:sanctum', 'verified', 'booking'])->group(function(){
    Route::get('/bookings/{booking}', [BookingController::class, 'show'])->name("booking");
});

Route::middleware(['auth:sanctum', 'verified', 'restaurant'])->group(function(){
    Route::get('/restaurants/{restaurant}/bookings', [RestaurantController::class, 'bookings'])->name("restaurant.bookings");
    Route::get('/restaurants/{restaurant}/bookings/{booking}', [RestaurantController::class, 'booking'])->name("restaurant.booking");
    Route::get('/restaurants/{restaurant}/manage', [RestaurantController::class, 'manage'])->name("restaurant.manage");
    Route::get('/restaurant/email/verify/{restaurant}/resend', [RestaurantController::class, 'resendVerificationEmail'])->name("restaurant.verify_email.resend");
});
