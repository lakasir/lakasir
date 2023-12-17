<?php

use App\Livewire\Counter;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return ['Laravel' => app()->version()];
});

Route::group(['prefix' => 'admin'], function ()
{
    Route::get('/counter', Counter::class);
});
