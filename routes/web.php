<?php

use App\Http\Controllers\Api\Tenants\Reports\PurchasingReportController;
use App\Http\Controllers\CashierReportController;
use App\Http\Controllers\PrinterController;
use App\Http\Controllers\ProductReportController;
use App\Http\Controllers\SellingReportController;
use App\Livewire\ResetPassword;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Volt::route('/', 'pages/welcome');

Route::view('/offline', 'offline');

Route::get('/serviceworker.js', function () {
    return response()->file(public_path('serviceworker.js'))
        ->header('Content-Type', 'application/javascript');
});

Route::middleware([
    'web',
])
    ->prefix('admin')
    ->group(function () {
        //
    });

Route::middleware('web')->group(function () {
    Route::get('/', function () {
        return redirect()->to('/member');
    });
    Route::get('/member/purchasing-report/generate', PurchasingReportController::class)
        ->name('purchasing-report.generate');
    Route::get('/member/selling-report/generate', SellingReportController::class)
        ->name('selling-report.generate');
    Route::get('/member/product-report/generate', ProductReportController::class)
        ->name('product-report.generate');
    Route::get('/member/cashier-report/generate', CashierReportController::class)
        ->name('cashier-report.generate');
    Route::view('/member/sellings/{selling}/print', 'filament.tenant.pages.selling.print-receipt')
        ->name('selling.print');
    Route::get('/reset-password/{token}', ResetPassword::class)
        ->middleware('guest')
        ->name('reset-password.index');
});
