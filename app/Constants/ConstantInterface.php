<?php

namespace App\Constants;

interface ConstantInterface
{
    public static function all(): array;

    public static function getTitle(string $name): string;
}

