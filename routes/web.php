<?php

use App\Http\Controllers\Settings\General;
use App\Http\Controllers\Transaction\CashDrawer;
use App\Http\Controllers\Transaction\BillPurchasing;
use App\Http\Controllers\Transaction\Purchasing;
use App\Http\Controllers\User\Role;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\User\ChangePassword;
use App\Http\Controllers\User\Profile;
use App\Http\Controllers\Master\Customer;
use App\Http\Controllers\Master\CustomerType;
use App\Http\Controllers\Master\Group;
use App\Http\Controllers\Master\Supplier;
use App\Http\Controllers\Master\Item;
use App\Http\Controllers\Master\Category;
use App\Http\Controllers\Master\Unit;
use App\Http\Controllers\Dashboard;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

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
})->middleware(['installed', 'auth']);

Route::view('/completed', 'app.install.completed');

Route::get('/c', function () {
    return view('app.transaction.sellings.cashier');
})->name('cashier')->middleware('installed');

Route::group(['middleware' => ['installed', 'auth']], function () {
    Route::get('dashboard', Dashboard::class)->name('dashboard');
    Route::get('dashboard/data-selling', Dashboard::class)->name('data-selling');

    Route::group(['prefix' => 'master'], function () {
        Route::delete('/payment_method/bulk-destroy', [PaymentMethod::class, 'bulkDestroy']);
        Route::resource('/payment_method', PaymentMethod::class);

        Route::delete('/unit/bulk-destroy', [Unit::class, 'bulkDestroy']);
        Route::resource('/unit', Unit::class);

        Route::delete('/category/bulk-destroy', [Category::class, 'bulkDestroy']);
        Route::resource('/category', Category::class);

        Route::get('/item/download-template', [Item::class, 'downloadTemplate'])->name('item.download-template');
        Route::post('/item/import', [Item::class, 'importTemplate'])->name('item.import');
        Route::delete('/item/bulk-destroy', [Item::class, 'bulkDestroy']);
        Route::resource('/item', Item::class);


        Route::get('/supplier/download-template', [Supplier::class, 'downloadTemplate'])->name('supplier.download-template');
        Route::post('/supplier/import', [Supplier::class, 'importTemplate'])->name('supplier.import');
        Route::delete('/supplier/bulk-destroy', [Supplier::class, 'bulkDestroy']);
        Route::resource('/supplier', Supplier::class);

        Route::delete('/group/bulk-destroy', [Group::class, 'bulkDestroy']);
        Route::resource('/group', Group::class);

        Route::delete('/type_customer/bulk-destroy', [CustomerType::class, 'bulkDestroy']);
        Route::resource('/type_customer', CustomerType::class);

        Route::delete('/customer/bulk-destroy', [Customer::class, 'bulkDestroy']);
        Route::resource('/customer', Customer::class);

        Route::post('/customer-point', 'Master\CustomerPoint@store')->name('customer-point.store');
    });

    Route::group(['prefix' => 'user'], function () {
        Route::get('profile', [Profile::class, 'index'])->name('profile.index');
        Route::post('profile', [Profile::class, 'store'])->name('profile.store');

        Route::get('change_password', [ChangePassword::class, 'index'])->name('change_password.index');
        Route::post('change_password', [ChangePassword::class, 'store'])->name('change_password.store');

        Route::delete('/bulk-destroy', [UserController::class, 'bulkDestroy']);
        Route::resource('/role', Role::class);
    });
    Route::resource('/user', UserController::class);

    Route::group(['prefix' => 'transaction'], function () {
        Route::get('/purchasing/{purchasing}/detail/{purchasing-detail}/edit', [Purchasing::class, 'editDetail'])->name('purchasing.detail.edit');
        Route::resource('/purchasing', Purchasing::class);
        Route::post('/purchasing/{purchasing}/paid/', [Purchasing::class, 'updatePaid'])->name('update-paid-purchasing');
        Route::resource('/bill_purchasing', BillPurchasing::class)->only('index');

        Route::get('/cashier', function () {
            get_lang();

            /* $token = $user->createToken('Create token from login ui')->accessToken; */
            /* dd($token); */
            /* $request->session()->put('bearer-token', $token); */

            Gate::authorize('browse-selling');

            $token = session()->get('bearer-token');

            return view('app.transaction.sellings.desktop')->with('token', "Bearer $token");
        });

        Route::resource('/selling', Selling::class)->only(['index', 'show']);
    });

    Route::post('/cashdrawer/open', [CashDrawer::class, 'open'])->name('cashdrawer.open');
    Route::post('/cashdrawer/close', [CashDrawer::class, 'close'])->name('cashdrawer.close');

    Route::group(['prefix' => 's', 'as' => 's.'], function () {
        Route::resource('/general', General::class)->only(['index']);
        Route::group(['prefix' => '/general', 'as' => 'general.'], function () {
            Route::resource('/company', Company::class)->only(['index', 'store']);
        });
        Route::resource('/default', DefaultSetting::class)->only(['index', 'store']);
    });
    Route::resource('/applications', App::class)->only('index');
});

Route::group(['middleware' => 'installed'], function () {
    Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register')->middleware('guest');
    Route::post('register', [RegisterController::class, 'register'])->name('register')->middleware('guest');
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login')->middleware('guest');
    Route::post('login', [LoginController::class, 'login'])->name('login');
    Route::post('logout', [LoginController::class, 'logout'])->name('logout');
});
