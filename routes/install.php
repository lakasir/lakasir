<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'checkinstalled'], function() {
    Route::get('/', 'Install\Install@show')->name('install.index');
    Route::post('/databaseStore', 'Install\Install@databaseStore')->name('install.databaseStore');
    Route::post('/userStore', 'Install\Install@userStore')->name('install.userStore');
    Route::post('/companyStore', 'Install\Install@companyStore')->name('install.companyStore');
    Route::post('/', 'Install\Install@store')->name('install.store');
});
