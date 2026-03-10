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
    Volt::route('/dashboard', 'pages/dashboard')->name('dashboard');

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
