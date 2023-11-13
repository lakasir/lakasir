<?php

declare(strict_types=1);

use App\Http\Controllers\Api\Tenants\Master\CategoryController;
use App\Http\Controllers\Api\Tenants\Master\MemberController;
use App\Http\Controllers\Api\Tenants\Master\ProductController;
use App\Http\Controllers\Api\Tenants\Transaction\SellingController;
use App\Http\Controllers\Api\Tenants\UploadController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Livewire\ResetPassword;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;


Route::middleware([
    'web',
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
])
->group(function () {
    Route::get('/reset-password/{token}', ResetPassword::class)
            ->middleware('guest')
            ->name('reset-password.index');
});

Route::middleware([
    'api',
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
])
->prefix('api')
->group(function () {
    Route::group(['prefix' => 'auth'], function () {
        Route::post('/login', [AuthenticatedSessionController::class, 'store'])
            ->name('login');

        Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
            ->middleware('guest')
            ->name('password.email');

        Route::get('/verify-email/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
            ->name('verification.verify');

        Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
            ->middleware(['auth', 'throttle:6,1'])
            ->name('verification.send');

        Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
            ->middleware('auth')
            ->name('logout');
    });

    Route::group(['prefix' => 'temp', 'middleware' => 'auth:sanctum'], function () {
        Route::post('upload', UploadController::class);
    });

    Route::group(['prefix' => 'master', 'middleware' => 'auth:sanctum'], function () {
        Route::resource('category', CategoryController::class)
            ->middleware("method_and_permission:index@read category|store@create category|show@read category|destroy@delete category|update@update category");
        Route::resource('product', ProductController::class)
            ->middleware("method_and_permission:index@read product|store@create product|show@read product|destroy@delete product|update@update product");
        Route::resource('member', MemberController::class)
            ->middleware("method_and_permission:index@read member|store@create member|show@read member|destroy@delete member|update@update member");
    });

    Route::group(['prefix' => 'transaction', 'middleware' => 'auth:sanctum'], function () {
        Route::resource('selling', SellingController::class)
            ->middleware("method_and_permission:index@read selling|store@create selling|show@read selling|destroy@delete selling|update@update selling");
    });

    Route::get('/', function () {
        return ['Laravel' => app()->version()];
    });

    Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
        return $request->user();
    });
});
