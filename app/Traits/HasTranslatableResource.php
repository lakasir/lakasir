<?php

namespace App\Traits;

use Filament\Navigation\NavigationItem;
use Filament\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;

trait HasTranslatableResource
{
    public static function getNavigationItems(): array
    {
        if (new self instanceof Page) {
            return self::pageNavigationItems();
        }

        return self::resourceNavigationItems();
    }

    public static function getNavigationLabel(): string
    {
        if (new self instanceof Page) {
            return self::getPageNavigationLabel();
        }

        return static::getTitleCasePluralModelLabel();
    }

    private static function getPageNavigationLabel(): string
    {
        if (static::$label ?? false) {
            return __(static::$label);
        }

        return static::$navigationLabel ?? static::$title ?? str(class_basename(static::class))
            ->kebab()
            ->replace('-', ' ')
            ->title();
    }

    private static function pageNavigationItems(): array
    {
        return [
            NavigationItem::make(__(static::getNavigationLabel()))
                ->group(static::getNavigationGroup())
                ->parentItem(static::getNavigationParentItem())
                ->icon(static::getNavigationIcon())
                ->activeIcon(static::getActiveNavigationIcon())
                ->isActiveWhen(fn (): bool => request()->routeIs(static::getNavigationItemActiveRoutePattern()))
                ->sort(static::getNavigationSort())
                ->badge(static::getNavigationBadge(), color: static::getNavigationBadgeColor())
                ->badgeTooltip(static::getNavigationBadgeTooltip())
                ->url(static::getNavigationUrl()),
        ];
    }

    private static function resourceNavigationItems(): array
    {
        return [
            NavigationItem::make(__(static::getNavigationLabel()))
                ->group(static::getNavigationGroup())
                ->parentItem(static::getNavigationParentItem())
                ->icon(static::getNavigationIcon())
                ->activeIcon(static::getActiveNavigationIcon())
                ->isActiveWhen(fn () => request()->routeIs(static::getRouteBaseName().'.*'))
                ->badge(static::getNavigationBadge(), color: static::getNavigationBadgeColor())
                ->badgeTooltip(static::getNavigationBadgeTooltip())
                ->sort(static::getNavigationSort())
                ->url(static::getNavigationUrl()),
        ];
    }

    public static function getLabel(): ?string
    {
        if (new static instanceof Page) {
            return static::getPageNavigationLabel();
        }

        return __(str(class_basename(static::$navigationLabel ?? static::$model))
            ->kebab()
            ->replace('-', ' ')
            ->title()->value());
    }

    public function getTitle(): string|Htmlable
    {
        return __(static::$title ?? (string) str(class_basename(static::class))
            ->kebab()
            ->replace('-', ' ')
            ->title());
    }
}
