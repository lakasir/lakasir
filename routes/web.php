<?php

use Illuminate\Support\Facades\Route;

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
