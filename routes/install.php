<?php

use App\Http\Controllers\Install\Install;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'checkinstalled'], function() {
    Route::get('/', [Install::class, 'show'])->name('install.index');
    Route::post('/databaseStore', [Install::class, 'databaseStore'])->name('install.databaseStore');
    Route::post('/userStore', [Install::class, 'userStore'])->name('install.userStore');
    Route::post('/companyStore', [Install::class, 'companyStore'])->name('install.companyStore');
    Route::post('/', [Install::class, 'store'])->name('install.store');
});
