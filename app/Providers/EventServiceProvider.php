<?php

namespace App\Providers;

use App\Models\Tenants\Member;
use App\Models\Tenants\Product;
use App\Models\Tenants\Selling;
use App\Observers\MemberObserver;
use App\Observers\ProductObserver;
use App\Observers\SellingObserver;
use App\Observers\TenantObserver;
use App\Tenant;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        \App\Events\SellingCreated::class => [
            \App\Listeners\AssignProduct::class,
        ],
    ];

    /**
     * The model observers for your application.
     *
     * @var array
     */
    protected $observers = [
        Member::class => [MemberObserver::class],
        Selling::class => [SellingObserver::class],
        Tenant::class => [TenantObserver::class],
        Product::class => [ProductObserver::class],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
