<?php

use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\BookingController;
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
Route::view('/contact-us', 'contact_us')->name('contact');
Route::view('/about-us', 'about-us')->name('about-us');
Route::get('/restaurant/{restaurant}', [RestaurantController::class, 'show'])->name("restaurant.show");
Route::get('/restaurant/email/verify/{restaurant}', [RestaurantController::class, 'verifyEmail'])->name("restaurant.verify_email");

Route::middleware(['auth:sanctum', 'admin'])->group(function(){
    Route::get('restaurants/status/{status}', [RestaurantController::class, 'status'])->name('restaurant.status');
});

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::view('/bookings', 'bookings.index')->name("bookings");
    Route::view('/dashboard', 'dashboard')->name('dashboard');
    Route::view('/my-restaurants', 'restaurant.manage.index')->name("my-restaurants");
    Route::get('/my-restaurants/bookings', [RestaurantController::class, 'bookingsSelect'])->name("restaurant.bookings_select");
});

Route::middleware(['auth:sanctum', 'booking'])->group(function(){
    Route::get('/bookings/{booking}', [BookingController::class, 'show'])->name("booking");
});

Route::middleware(['auth:sanctum', 'restaurant'])->group(function(){
    Route::get('/restaurants/{restaurant}/bookings', [RestaurantController::class, 'bookings'])->name("restaurant.bookings");
    Route::get('/restaurants/{restaurant}/bookings/{booking}', [RestaurantController::class, 'booking'])->name("restaurant.booking");
    Route::get('/restaurants/{restaurant}/manage', [RestaurantController::class, 'manage'])->name("restaurant.manage");
    Route::get('/restaurants/{restaurant}/stripe', [RestaurantController::class, 'stripe'])->name("restaurant.stripe");
});
