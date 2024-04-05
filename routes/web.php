<?php

use App\Livewire\Forms\RegisterTenantForm;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Volt::route('/', 'pages/welcome');

Route::get('/auth/register', RegisterTenantForm::class)
    ->name('auth.register');

Route::middleware([
    'web',
])
    ->prefix('admin')
    ->group(function () {
        //
    });
