<?php

use Illuminate\Support\Facades\Route;

Route::get("/restaurant/{restaurant}", [\App\Http\Controllers\App\RestaurantController::class, "show"]);
Route::get("/restaurant/{restaurant}/times", [\App\Http\Controllers\App\RestaurantController::class, "times"]);
Route::get("/restaurant/{restaurant}/book", [\App\Http\Controllers\App\RestaurantController::class, "book"]);
Route::post("/restaurant/{restaurant}/book", [\App\Http\Controllers\App\RestaurantController::class, "book"]);

Route::view("/app-login", "app.login")->name("app.login");
Route::post("/app-login", [\App\Http\Controllers\App\AuthController::class, "login"]);
Route::view("/app-register", "app.register")->name("app.register");
Route::post("/app-logout", [\App\Http\Controllers\App\AuthController::class, "logout"])->name("app.logout");
Route::get("/user", [\App\Http\Controllers\App\AuthController::class, "user"])->name("app.user");
