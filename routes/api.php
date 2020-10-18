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

Route::group(['middelware' => ['api'], 'prefix' => 'v1'], function() {
    Route::post('authenticate', [App\Http\Controllers\Auth\LoginController::class, 'authenticate'])->name('authenticate'); 
    
    Route::post('register', [App\Http\Controllers\Auth\RegisterController::class, 'register'])->name('reqister'); 
    Route::post('confirm/account', [App\Http\Controllers\Auth\VerificationController::class, 'accountActivation']);

    // TODO Implement
    // Route::post('reset-password',  [App\Http\Controllers\Auth\ResetPasswordController::class, 'reset']);
    // Need to be Authenticated before accessing these routes
    Route::group(['middleware' => 'auth.jwt'], function () {
        Route::group(['middleware' => 'token.confirm'], function () {
           
        });
    });
});

