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

Route::group(['middleware' => 'auth'], function() {
    Route::delete('/master/unit/bulk-destroy', 'Master\Unit@bulkDestroy');
    Route::resource('master/unit', 'Master\Unit');
});
