<?php

namespace App\Filament\Clusters\Traits;

use Filament\Pages\SubNavigationPosition;

/*
 * @use \Filament\Resources\Resource
 */
trait HasSubNavigationPosition
{
    public static function getSubNavigationPosition(): SubNavigationPosition
    {
        return (new static::$cluster)->getSubNavigationPosition();
    }
}
