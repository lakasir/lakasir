<?php

use App\Livewire\Forms\Auth\RegisterTenantForm;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Volt::route('/', 'pages/welcome');

Route::view('/offline', 'offline');

Route::get('/serviceworker.js', function () {
    return response()->file(public_path('serviceworker.js'))
        ->header('Content-Type', 'application/javascript');
});

Route::get('/auth/register', RegisterTenantForm::class)
    ->name('auth.register');

Route::middleware([
    'web',
])
    ->prefix('admin')
    ->group(function () {
        //
    });

Route::middleware('guest')->group(function () {
    Route::get('/member/login', \App\Livewire\Auth\Login::class)->name('filament.tenant.auth.login');
});

Route::middleware(['web', 'auth'])->prefix('member')->group(function () {
    Route::post('/logout', function (\Illuminate\Http\Request $request) {
        \Illuminate\Support\Facades\Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('filament.tenant.auth.login');
    })->name('logout');
    
    Route::get('/', \App\Livewire\Dashboard\Index::class)->name('dashboard');
    Route::get('/products', \App\Livewire\Products\Index::class)->name('products.index');
    Route::get('/categories', \App\Livewire\Categories\Index::class)->name('categories.index');
    Route::get('/vouchers', \App\Livewire\Vouchers\Index::class)->name('vouchers.index');
    Route::get('/pos', \App\Livewire\Pos\Index::class)->name('pos.index');

    // Master Data
    Route::get('/members', \App\Livewire\Members\Index::class)->name('members.index');
    Route::get('/suppliers', \App\Livewire\Suppliers\Index::class)->name('suppliers.index');
    Route::get('/payment-methods', \App\Livewire\PaymentMethods\Index::class)->name('payment-methods.index');
    Route::get('/tables', \App\Livewire\Tables\Index::class)->name('tables.index');
    
    // Inventory & Purchasing
    Route::get('/purchasings', \App\Livewire\Purchasings\Index::class)->name('purchasings.index');
    Route::get('/purchasings/{purchasing}', \App\Livewire\Purchasings\Show::class)->name('purchasings.show');
    
    // Stock Opname
    Route::get('/stock-opnames', \App\Livewire\StockOpnames\Index::class)->name('stock-opnames.index');
    Route::get('/stock-opnames/{stockOpname}', \App\Livewire\StockOpnames\Show::class)->name('stock-opnames.show');
    
    // Receivable (Piutang)
    Route::get('/receivables', \App\Livewire\Receivables\Index::class)->name('receivables.index');
    Route::get('/receivables/{receivable}', \App\Livewire\Receivables\Show::class)->name('receivables.show');

    // Sellings (Transaction History)
    Route::get('/sellings', \App\Livewire\Sellings\Index::class)->name('sellings.index');
    Route::get('/sellings/{selling}', \App\Livewire\Sellings\Show::class)->name('sellings.show');

    // Users & Roles
    Route::get('/users', \App\Livewire\Users\Index::class)->name('users.index');
    Route::get('/roles', \App\Livewire\Roles\Index::class)->name('roles.index');
});
