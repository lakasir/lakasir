<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function() {

})->middleware('installed');

Route::view('/completed', 'app.install.completed');

Route::group(['middleware' => [ 'auth', 'installed' ]], function() {
    Route::get('dashboard', 'Dashboard')->name('dashboard');

    Route::delete('/master/unit/bulk-destroy', 'Master\Unit@bulkDestroy');
    Route::resource('master/unit', 'Master\Unit');

    Route::delete('/master/category/bulk-destroy', 'Master\Category@bulkDestroy');
    Route::resource('master/category', 'Master\Category');


    Route::delete('/master/item/bulk-destroy', 'Master\Item@bulkDestroy');
    Route::resource('master/item', 'Master\Item');

    Route::delete('/master/supplier/bulk-destroy', 'Master\Supplier@bulkDestroy');
    Route::resource('master/supplier', 'Master\Supplier');

    Route::delete('/master/group/bulk-destroy', 'Master\Group@bulkDestroy');
    Route::resource('master/group', 'Master\Group');
});

Route::group(['middleware' => 'installed'], function() {
    Auth::routes();
});
