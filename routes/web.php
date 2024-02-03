<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Volt::route('/', 'pages/welcome');

Route::middleware([
    'web',
])
    ->prefix('admin')
    ->group(function () {
        //
    });
