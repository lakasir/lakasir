<?php

namespace App\Providers;

use App\Events\RecalculateEvent;
use App\Events\SellingCreated;
use App\Listeners\AdjustProduct;
use App\Listeners\AssignProduct;
use App\Listeners\CreateReceivableIfCredit;
use App\Models\Tenants\Member;
use App\Models\Tenants\Product;
use App\Models\Tenants\Selling;
use App\Models\Tenants\StockOpname;
use App\Models\Tenants\User;
use App\Observers\MemberObserver;
use App\Observers\ProductObserver;
use App\Observers\SellingObserver;
use App\Observers\StockOpnameObserver;
use App\Observers\UserObserver;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        SellingCreated::class => [
            AssignProduct::class,
            CreateReceivableIfCredit::class,
        ],
        RecalculateEvent::class => [
            AdjustProduct::class,
        ],
    ];

    protected $observers = [
        Member::class => [MemberObserver::class],
        Selling::class => [SellingObserver::class],
        Product::class => [ProductObserver::class],
        User::class => [UserObserver::class],
        StockOpname::class => [StockOpnameObserver::class],
    ];

    public function boot()
    {
    }

    public function shouldDiscoverEvents()
    {
        return false;
    }
}
