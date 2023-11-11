<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisteredUserController;

Route::group(['prefix' => 'auth'], function ()
{
    Route::post('/register', [RegisteredUserController::class, 'store'])
        ->name('register');
});
