<?php

use App\Http\Controllers\Api\Master\CategoryController;
use App\Http\Controllers\Api\Master\MemberController;
use App\Http\Controllers\Api\Master\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;

Route::group(['prefix' => 'auth'], function ()
{
    Route::post('/register', [RegisteredUserController::class, 'store'])
        ->name('register');

    Route::post('/login', [AuthenticatedSessionController::class, 'store'])
        ->name('login');

    Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
        ->middleware('guest')
        ->name('password.email');

    Route::post('/reset-password', [NewPasswordController::class, 'store'])
        ->middleware('guest')
        ->name('password.update');

    Route::get('/verify-email/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
        ->middleware(['auth', 'signed', 'throttle:6,1'])
        ->name('verification.verify');

    Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware(['auth', 'throttle:6,1'])
        ->name('verification.send');

    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
        ->middleware('auth')
        ->name('logout');
});

Route::group(['prefix' => 'master', 'middleware' => 'auth:sanctum'], function ()
{
    Route::resource('category', CategoryController::class)
        ->middleware("method_and_permission:index@read category|store@create category|show@read category|destroy@delete category|update@update category");
    Route::resource('product', ProductController::class)
        ->middleware("method_and_permission:index@read product|store@create product|show@read product|destroy@delete product|update@update product");
    Route::resource('member', MemberController::class)
        ->middleware("method_and_permission:index@read member|store@create member|show@read member|destroy@delete member|update@update member");
});
Route::group(['prefix' => 'transaction', 'middleware' => 'auth:sanctum'], function ()
{
    Route::resource('selling', CategoryController::class)
        ->middleware("method_and_permission:index@read selling|store@create selling|show@read selling|destroy@delete selling|update@update selling");
});

Route::get('/', function ()
{
    return ['Laravel' => app()->version()];
});

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

