<?php

namespace App\Filament\Tenant\Resources\SellingResource\Pages;

use App\Filament\Tenant\Resources\SellingResource;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Contracts\Support\Htmlable;

class ViewSelling extends ViewRecord
{
    protected static string $resource = SellingResource::class;

    public function getTitle(): string|Htmlable
    {
        return 'View '.$this->getRecord()->code;
    }
}
