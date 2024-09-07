<?php

namespace App\Filament\Tenant\Pages\Traits;

use App\Filament\Tenant\Pages\CashierReport;
use App\Filament\Tenant\Pages\ProductReport;
use App\Filament\Tenant\Pages\PurchasingReport;
use App\Filament\Tenant\Pages\SellingReport;
use AymanAlhattami\FilamentPageWithSidebar\FilamentPageSidebar;
use AymanAlhattami\FilamentPageWithSidebar\PageNavigationItem;
use AymanAlhattami\FilamentPageWithSidebar\Traits\HasPageSidebar;
use Filament\Pages\Page;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

trait HasReportPageSidebar
{
    use HasPageSidebar;

    public static function sidebar(): FilamentPageSidebar
    {
        return FilamentPageSidebar::make()
            ->topbarNavigation()
            ->setNavigationItems([
                static::generateNavigationItem(SellingReport::class),
                static::generateNavigationItem(ProductReport::class),
                static::generateNavigationItem(CashierReport::class),
                static::generateNavigationItem(PurchasingReport::class),
            ]);
    }

    private static function generateNavigationItem(string $resource, ?string $feature = null): PageNavigationItem
    {
        $canAccess = $feature ? feature($feature) && $resource::canAccess() : $resource::canAccess();

        $active = false;
        if ((new $resource) instanceof Page) {
            $active = Str::of($resource::getRouteName())->exactly(Route::current()->getName());
        }

        if ((new $resource) instanceof Resource) {
            $active = Str::of(Route::currentRouteName())->contains($resource::getRouteBaseName());
        }

        return PageNavigationItem::make($resource::getLabel())
            ->visible($canAccess)
            ->icon($resource::getNavigationIcon())
            ->isActiveWhen(fn (): bool => $active)
            ->url(fn (): string => $resource::getUrl());
    }
}
