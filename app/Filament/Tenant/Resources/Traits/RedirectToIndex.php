<?php

namespace App\Filament\Tenant\Resources\Traits;

trait RedirectToIndex
{
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getNavigationUrl();
    }
}
