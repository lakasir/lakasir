<?php

declare(strict_types=1);

use App\Http\Controllers\Api\Tenants\AboutController;
use App\Http\Controllers\Api\Tenants\Master\CategoryController;
use App\Http\Controllers\Api\Tenants\Master\MemberController;
use App\Http\Controllers\Api\Tenants\Master\Product\StockController;
use App\Http\Controllers\Api\Tenants\Master\ProductController;
use App\Http\Controllers\Api\Tenants\Master\SupplierController;
use App\Http\Controllers\Api\Tenants\NotificationController;
use App\Http\Controllers\Api\Tenants\PaymentMethodController;
use App\Http\Controllers\Api\Tenants\ProfileController;
use App\Http\Controllers\Api\Tenants\RegisterFCMTokenController;
use App\Http\Controllers\Api\Tenants\SettingController;
use App\Http\Controllers\Api\Tenants\Settings\SecureInitialPriceController;
use App\Http\Controllers\Api\Tenants\Transaction\CashDrawerController;
use App\Http\Controllers\Api\Tenants\Transaction\DashboardController;
use App\Http\Controllers\Api\Tenants\Transaction\SellingController;
use App\Http\Controllers\Api\Tenants\Transaction\VoucherController;
use App\Http\Controllers\Api\Tenants\UploadController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\PrinterController;
use App\Http\Controllers\ReportController;
use App\Http\Middleware\InitializeTenancyByDomain;
use App\Livewire\ResetPassword;
use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

Route::middleware([
    'web',
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
])
    ->group(function () {
        Route::get('/', function () {
            return redirect()->to('/member');
        });
        // Report PDFs: the Filament tenant panel's own guard and each report's
        // permission (the seeder gives them to the admin role). All four use
        // ReportController; the route's `type` default picks the service, the
        // view and the permission.
        Route::middleware('auth:web')->group(function () {
            foreach (['selling', 'product', 'cashier', 'purchasing'] as $report) {
                Route::get("/member/{$report}-report/generate", ReportController::class)
                    ->defaults('type', $report)
                    ->can("generate {$report} report")
                    ->name("{$report}-report.generate");
            }
        });
        Route::get('/reset-password/{token}', ResetPassword::class)
            ->middleware('guest')
            ->name('reset-password.index');
    });

Route::middleware([
    'api',
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
])
    ->prefix('api')
    ->group(function () {
        // The POS app checks a shop domain with this before signing in, so it
        // stays public and answers only whether the shop exists.
        Route::get('check', function () {
            return response()->json([
                'tenant' => tenant('id'),
            ]);
        });
        Route::group(['prefix' => 'auth'], function () {
            Route::post('/login', [AuthenticatedSessionController::class, 'store'])
                ->name('login');

            Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
                ->middleware('guest')
                ->name('password.email');

            Route::get('/verify-email/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
                ->name('verification.verify');

            Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
                ->middleware(['auth', 'throttle:6,1'])
                ->name('verification.send');

            Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
                ->middleware('auth:sanctum')
                ->middleware('auth')
                ->name('logout');

            Route::get('/me', [ProfileController::class, 'index'])
                ->middleware('auth:sanctum')
                ->name('me');

            Route::put('/me', [ProfileController::class, 'update'])
                ->middleware('auth:sanctum')
                ->name('profile.update');
        });

        Route::group(['middleware' => 'auth:sanctum'], function () {
            Route::post('/register-fcm-token', RegisterFCMTokenController::class);

            Route::group(['prefix' => 'temp'], function () {
                Route::post('upload', UploadController::class);
            });

            Route::group(['prefix' => 'master'], function () {
                Route::resource('/supplier', SupplierController::class)
                    ->middleware('method_and_permission:index@read supplier|store@create supplier|show@read supplier|destroy@delete supplier|update@update supplier');
                Route::group(['prefix' => '/category'], function () {
                    Route::get('/', [CategoryController::class, 'index'])->can('read category');
                    Route::post('/', [CategoryController::class, 'store'])->can('create category');
                    Route::get('/{category}', [CategoryController::class, 'show'])->can('read category');
                    Route::put('/{category}', [CategoryController::class, 'update'])->can('update category');
                    Route::delete('/{category}', [CategoryController::class, 'destroy'])->can('delete category');
                });

                Route::group(['prefix' => '/product'], function () {
                    Route::get('/', [ProductController::class, 'index'])->can('read product');
                    Route::post('/', [ProductController::class, 'store'])->can('create product');
                    Route::get('/{product}', [ProductController::class, 'show'])->can('read product');
                    Route::put('/{product}', [ProductController::class, 'update'])->can('update product');
                    Route::delete('/{product}', [ProductController::class, 'destroy'])->can('delete product');
                    Route::get('/{product}/stock', [StockController::class, 'index'])->can('read product stock');
                    Route::post('/{product}/stock', [StockController::class, 'store'])->can('create product stock');
                    Route::delete('/{product}/stock/{stock}', [StockController::class, 'destroy'])->can('delete product stock');
                });

                Route::resource('member', MemberController::class)
                    ->middleware('method_and_permission:index@read member|store@create member|show@read member|destroy@delete member|update@update member');

                Route::group(['prefix' => 'payment-method'], function () {
                    Route::get('/', [PaymentMethodController::class, 'index'])->can('read payment method');
                });

            });

            Route::group(['prefix' => 'about'], function () {
                // Readable by any signed-in user: the POS app needs the shop's
                // name and address to print a receipt.
                Route::get('/', [AboutController::class, 'index'])
                    ->name('about');
                Route::put('/', [AboutController::class, 'update'])
                    ->can('update about')
                    ->name('about.update');
            });

            Route::group(['prefix' => 'transaction'], function () {
                Route::get('/dashboard/total-revenue', [DashboardController::class, 'totalRevenue'])
                    ->can('read revenue overview')
                    ->name('transaction.dashboard.total-revenue');
                Route::get('/dashboard/total-gross-profit', [DashboardController::class, 'totalGrossProfit'])
                    ->can('read revenue overview')
                    ->name('transaction.dashboard.total-gross-profit');
                Route::get('/dashboard/total-sales', [DashboardController::class, 'totalSales'])
                    ->can('read sales overview')
                    ->name('transaction.dashboard.total-sales');

                Route::get('/selling', [SellingController::class, 'index'])->can('read selling');
                Route::post('/selling', [SellingController::class, 'store'])->can('create selling');
                Route::post('/voucher/check', [VoucherController::class, 'check'])
                    ->can('create selling')
                    ->name('transaction.voucher.check');
                Route::get('/selling/{selling}', [SellingController::class, 'show'])->can('read selling');
                Route::group(['prefix' => 'cash-drawer'], function () {
                    Route::get('/', [CashDrawerController::class, 'show']);
                    Route::get('/summary', [CashDrawerController::class, 'summary'])
                        ->name('transaction.cash-drawer.summary');
                    Route::post('/', [CashDrawerController::class, 'store'])->can('open cash drawer');
                    Route::post('/close', [CashDrawerController::class, 'close'])->can('close cash drawer');
                });
            });

            Route::get('setting/{key}', [SettingController::class, 'show'])
                ->can('read setting')
                ->name('setting.show');
            Route::post('setting', [SettingController::class, 'store'])
                ->can('update setting')
                ->name('setting.store');

            Route::post('setting/secure-initial-price', [SecureInitialPriceController::class, 'store'])
                ->name('setting.secure-initial-price.store')
                ->can('enable secure initial price');

            Route::post('setting/secure-initial-price/verify', [SecureInitialPriceController::class, 'verify'])
                ->name('setting.secure-initial-price.verify')
                ->can('verify secure initial price');

            // The API's cashier report is the same PDF as the web one, and
            // keeps its original POST contract.
            Route::post('/report/cashier', ReportController::class)
                ->defaults('type', 'cashier')
                ->name('api.report.cashier');

            Route::group(['prefix' => 'printer'], function () {
                Route::get('/', [PrinterController::class, 'index'])
                    ->can('read printer');
                Route::post('/', [PrinterController::class, 'store'])
                    ->can('create printer');
                Route::put('/{printer}', [PrinterController::class, 'update'])
                    ->can('update printer');
                Route::delete('/{printer}', [PrinterController::class, 'destroy'])
                    ->can('delete printer');
            });

            Route::group(['prefix' => 'notification'], function () {
                Route::get('/', [NotificationController::class, 'index'])
                    ->name('notification.index');
                Route::put('/{notification}/{product}', [NotificationController::class, 'update'])
                    ->name('notification.update');
                Route::delete('/clear', [NotificationController::class, 'clear'])
                    ->name('notification.destroy');
            });
        });
    });
