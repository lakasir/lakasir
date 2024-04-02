<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Volt::route('/', 'pages/welcome');
Volt::route('/auth/register', 'pages/auth/register')->name('auth.register');

Route::middleware([
    'web',
])
    ->prefix('admin')
    ->group(function () {
        //
    });
