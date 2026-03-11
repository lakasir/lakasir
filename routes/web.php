<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Volt::route('/', 'pages/welcome');

Route::view('/offline', 'offline');

Route::get('/serviceworker.js', function () {
    return response()->file(public_path('serviceworker.js'))
        ->header('Content-Type', 'application/javascript');
});

Route::middleware('guest')->group(function () {
    Volt::route('/login', 'pages/auth/login')->name('login');

    Volt::route('/auth/register', 'pages/auth/register')->name('auth.register');
});

Route::middleware('auth')->group(function () {
    Route::redirect('/dashboard', '/app/dashboard');

    Route::prefix('app')->group(function () {
        Volt::route('/dashboard', 'pages/dashboard')->name('dashboard');

        Route::prefix('settings')->group(function () {
            Volt::route('/', 'pages/settings/general')->name('settings.general');
            Volt::route('/category', 'pages/settings/category')->name('settings.category');
            Volt::route('/users', 'pages/settings/users')->name('settings.users');
            Volt::route('/roles', 'pages/settings/roles')->name('settings.roles');
            Volt::route('/printer', 'pages/settings/printer')->name('settings.printer');
            Volt::route('/about', 'pages/settings/about')->name('settings.about');
            Volt::route('/profile', 'pages/settings/profile')->name('settings.profile');
        });
    });

    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
});

Route::middleware([
    'web',
])
    ->prefix('admin')
    ->group(function () {
        //
    });
