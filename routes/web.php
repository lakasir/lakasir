<?php

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;

/**
 * Jika kamu tidak menyelesaikan ini, kamu punya hutang dengan diri kamu sendiri
 * semangat, semua tujuan yang baik pasti akan menghasilkan output yang baik.
 * proect ini gratis. boleh kamu jual dengan nama kamu, boleh kamu kustom sesuasi keinginan kamu, boleh kamu hapus tulisan ini,
 * dan juga boleh kamu hapus licecnsinya.
 *
 * yang terakhir semoga kita diberikan kemudahan rizki dan hati
 */

Route::get('/', function () {
    return redirect()->to('/dashboard');
})->middleware([ 'installed', 'auth' ]);

Route::view('/completed', 'app.install.completed');

Route::get('/c', function ()
{
    return view('app.transaction.sellings.cashier');
})->name('cashier')->middleware('installed');

Route::group(['middleware' => [ 'installed', 'auth' ]], function () {
    Route::get('dashboard', 'Dashboard')->name('dashboard');
    Route::get('dashboard/data-selling', 'Dashboard')->name('data-selling');

    Route::group(['prefix' => 'master'], function () {
        Route::delete('/payment_method/bulk-destroy', 'Master\PaymentMethod@bulkDestroy');
        Route::resource('/payment_method', 'Master\PaymentMethod');

        Route::delete('/unit/bulk-destroy', 'Master\Unit@bulkDestroy');
        Route::resource('/unit', 'Master\Unit');

        Route::delete('/category/bulk-destroy', 'Master\Category@bulkDestroy');
        Route::resource('/category', 'Master\Category');

        Route::get('/item/download-template', 'Master\Item@downloadTemplate')->name('item.download-template');
        Route::post('/item/import', 'Master\Item@importTemplate')->name('item.import');
        Route::delete('/item/bulk-destroy', 'Master\Item@bulkDestroy');
        Route::resource('/item', 'Master\Item');


        Route::get('/supplier/download-template', 'Master\Supplier@downloadTemplate')->name('supplier.download-template');
        Route::post('/supplier/import', 'Master\Supplier@importTemplate')->name('supplier.import');
        Route::delete('/supplier/bulk-destroy', 'Master\Supplier@bulkDestroy');
        Route::resource('/supplier', 'Master\Supplier');

        Route::delete('/group/bulk-destroy', 'Master\Group@bulkDestroy');
        Route::resource('/group', 'Master\Group');

        Route::delete('/type_customer/bulk-destroy', 'Master\CustomerType@bulkDestroy');
        Route::resource('/type_customer', 'Master\CustomerType');

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
        Route::get('/purchasing/{purchasing}/detail/{purchasing-detail}/edit', 'Transaction\Purchasing@editDetail')->name('purchasing.detail.edit');
        Route::resource('/purchasing', 'Transaction\Purchasing');
        Route::post('/purchasing/{purchasing}/paid/', 'Transaction\Purchasing@updatePaid')->name('update-paid-purchasing');
        Route::resource('/bill_purchasing', 'Transaction\BillPurchasing')->only('index');

        Route::get('/cashier', function ()
        {
            get_lang();

            /* $token = $user->createToken('Create token from login ui')->accessToken; */
            /* dd($token); */
            /* $request->session()->put('bearer-token', $token); */

            Gate::authorize('browse-selling');

            $token = session()->get('bearer-token');

            return view('app.transaction.sellings.desktop')->with('token', "Bearer $token");
        });

        Route::resource('/selling', 'Transaction\Selling')->only(['index', 'show']);
    });

    Route::post('/cashdrawer/open', 'Transaction\CashDrawer@open')->name('cashdrawer.open');
    Route::post('/cashdrawer/close', 'Transaction\CashDrawer@close')->name('cashdrawer.close');

    Route::group(['prefix' => 's', 'as' => 's.'], function ()
    {
        Route::resource('/general', 'Settings\General')->only(['index']);
        Route::group(['prefix' => '/general', 'as' => 'general.'], function ()
        {
            Route::resource('/company', 'Settings\General\Company')->only(['index', 'store']);
        });
        Route::resource('/default', 'Settings\DefaultSetting')->only(['index', 'store']);
    });
    Route::resource('/applications', 'Lakasir\App')->only('index');
});

Route::group(['middleware' => 'installed'], function () {
    Auth::routes();
});
