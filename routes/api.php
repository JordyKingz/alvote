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
    Route::group(['middleware' => 'jwt.verify'], function () {
        // Rooms
        Route::get('room/get', [App\Http\Controllers\ConferenceRoomController::class, 'get']);
        Route::get('room/find/{id}', [App\Http\Controllers\ConferenceRoomController::class, 'show']);
        Route::post('room/create', [App\Http\Controllers\ConferenceRoomController::class, 'create']);
        Route::put('room/open', [App\Http\Controllers\ConferenceRoomController::class, 'open']);
        Route::put('room/close', [App\Http\Controllers\ConferenceRoomController::class, 'close']);
        Route::delete('room/delete/{id}', [App\Http\Controllers\ConferenceRoomController::class, 'destroy']);
        
        // Invite member
        Route::post('member/invite', [App\Http\Controllers\MemberController::class, 'invite']);

        // Votes
        Route::post('vote/create', [App\Http\Controllers\VoteController::class, 'create']);
    });

    // Member join room
    Route::post('room/join', [App\Http\Controllers\ConferenceRoomController::class, 'join']);
});

