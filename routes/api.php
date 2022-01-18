<?php

use App\Http\Controllers\Api\Auth\Profile;
use App\Http\Controllers\Api\Auth\Login;
use App\Http\Controllers\Api\Transaction\Selling;
use App\Http\Controllers\Api\CheckValidation;
use App\Http\Controllers\Api\Item;
use App\Http\Controllers\Transaction\Purchasing;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/formvalidation', CheckValidation::class)->withoutMiddleware('throttle');
Route::get('/item/{id}', Item::class)->name('api.item.show');

Route::group(['middleware' => 'auth:api'], function () {
    Route::post('/purchasing/store', [Purchasing::class, 'store'])->name('purchasing.store');
    Route::post('/auth/login', [Login::class, 'login'])->name('auth.login');
    Route::group(['middleware' => ['auth:api']], function () {
        Route::resource('/auth/profile', Profile::class)->only(['index', 'store']);
        Route::get('/selling/activity', [Selling::class, 'activity'])->name('selling.activity');
        Route::resource('/selling', Selling::class);
        Route::resource('/payment_method', PaymentMethod::class)->only(['index']);
    });
});
