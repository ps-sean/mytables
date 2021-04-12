<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/auth/register', [\App\Http\Controllers\ApiController::class, 'register']);

Route::post('/auth/login', [\App\Http\Controllers\ApiController::class, 'login']);

Route::middleware('auth:sanctum')->group(function(){
    Route::get('/user', [\App\Http\Controllers\ApiController::class, 'user']);

    Route::post('/auth/logout', [\App\Http\Controllers\ApiController::class, 'logout']);
});
