<?php

use App\Http\Controllers\Auth\RegisteredUserController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'domain'], function () {
    Route::post('/register', RegisteredUserController::class)
        ->name('register');
});

Route::get('/test', function () {
    return response()->json([
        'message' => 'Success!',
    ]);
});
