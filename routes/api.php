<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// Auth

Route::post('/login', 'AuthController@login');
Route::middleware(['auth:api', 'authJWT'])->group(function () {
    Route::post('/logout', 'AuthController@logout');
    Route::get('/me', 'AuthController@me');
    Route::post('/change', 'AuthController@changePassword');
});
