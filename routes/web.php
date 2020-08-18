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

Route::get('/', function () {
    return redirect()->to('/dashboard');
})->middleware([ 'installed', 'auth' ]);

Route::view('/completed', 'app.install.completed');


Route::group(['middleware' => [ 'auth', 'installed' ]], function () {
    Route::get('dashboard', 'Dashboard')->name('dashboard');

    Route::group(['prefix' => 'master'], function () {
        Route::delete('/unit/bulk-destroy', 'Master\Unit@bulkDestroy');
        Route::resource('/unit', 'Master\Unit');

        Route::delete('/category/bulk-destroy', 'Master\Category@bulkDestroy');
        Route::resource('/category', 'Master\Category');


        Route::delete('/item/bulk-destroy', 'Master\Item@bulkDestroy');
        Route::resource('/item', 'Master\Item');

        Route::delete('/supplier/bulk-destroy', 'Master\Supplier@bulkDestroy');
        Route::resource('/supplier', 'Master\Supplier');

        Route::delete('/group/bulk-destroy', 'Master\Group@bulkDestroy');
        Route::resource('/group', 'Master\Group');

        Route::delete('/customer/bulk-destroy', 'Master\Customer@bulkDestroy');
        Route::resource('/customer', 'Master\Customer');

        Route::post('/customer-point', 'Master\CustomerPoint@store')->name('customer-point.store');
    });

    Route::group(['prefix' => 'user'], function () {
        Route::get('profile', 'User\Profile@index')->name('profile.index');
        Route::post('profile', 'User\Profile@store')->name('profile.store');

        Route::get('change_password', 'User\ChangePassword@index')->name('change_password.index');
        Route::post('change_password', 'User\ChangePassword@store')->name('change_password.store');

        Route::delete('/bulk-destroy', 'User\UserController@bulkDestroy');
        Route::resource('/role', 'User\Role');
    });
    Route::resource('/user', 'User\UserController');

    Route::group(['prefix' => 'transaction'], function () {
        Route::resource('/purchasing', 'Transaction\Purchasing');
        Route::resource('/selling', 'Transaction\Selling');
        Route::get('/cashier', 'Transaction\Selling@cashier');
    });

    Route::post('/cashdrawer/open', 'Transaction\CashDrawer@open')->name('cashdrawer.open');
    Route::post('/cashdrawer/close', 'Transaction\CashDrawer@close')->name('cashdrawer.close');
});

Route::group(['middleware' => 'installed'], function () {
    Auth::routes();
});
