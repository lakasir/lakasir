<?php

namespace App\Modules\Abstracts;

use App\Traits\HasTranslatableResource;
use Filament\Resources\Resource;

abstract class ModuleResource extends Resource
{
    use HasTranslatableResource;

    public static function getSlug(): string
    {
        $namespace = str(static::class);

        $parts = explode('\\', $namespace);

        $moduleUrlPrefix = implode('/', array_slice($parts, 0, 2));

        $prefixUrl = str(str($moduleUrlPrefix)->explode('/')->get(1))->snake('-');

        $classBaseName = str(class_basename(static::class))->beforeLast('Resource')->snake('-');

        return "modules/$prefixUrl/$classBaseName";
    }
}
