<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'web',
])
    ->prefix('admin')
    ->group(function () {
     // 
    });
