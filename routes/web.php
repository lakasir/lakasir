<?php

use App\Livewire\Forms\Auth\RegisterTenantForm;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Volt::route('/', 'pages/welcome');

Route::view('/offline', 'offline');

Route::get('/serviceworker.js', function () {
    return response()->file(public_path('serviceworker.js'))
        ->header('Content-Type', 'application/javascript');
});

Route::get('/auth/register', RegisterTenantForm::class)
    ->name('auth.register');

Route::middleware([
    'web',
])
    ->prefix('admin')
    ->group(function () {
        //
    });
