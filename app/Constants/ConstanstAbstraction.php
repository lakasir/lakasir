<?php

namespace App\Constants;

use Illuminate\Support\Str;
use ReflectionClass;

/** @package App\Const */
abstract class ConstanstAbstraction implements ConstantInterface
{
    public static function all(): array
    {
        $constants = collect((new ReflectionClass(static::class))->getConstants());
        $values = collect();
        $constants->each(
            static function ($constant, $index) use ($values) {
                $values->put($constant, Str::title(str_replace('_', ' ', $index)));
            }
        );

        return $values->toArray();
    }

    public static function getTitle(string $name): string
    {
        $constants = static::all();

        return $constants[$name];
    }

    public static function getValues(): array
    {
        $constants = collect((new ReflectionClass(static::class))->getConstants());
        $values = collect();
        $constants->each(
            static function ($constant, $index) use ($values) {
                $values->put($index, $constant);
            }
        );

        return $values->toArray();
    }
}

