<?php

namespace App\Filament\Tenant\Resources\SellingResource\Pages;

use App\Filament\Tenant\Resources\SellingResource;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Contracts\Support\Htmlable;

class PrintInvoice extends ViewRecord
{
    protected static string $resource = SellingResource::class;

    public function getView(): string
    {
        return 'filament.tenant.resources.sellings.pages.print-invoice';
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([]);
    }

    public function getBreadcrumbs(): array
    {
        $resource = static::getResource();
        $viewUrl = $this->getResource()::getUrl('view', [
            'record' => $this->getRecord()->id,
        ]);

        $breadcrumbs = [
            $resource::getUrl() => $resource::getBreadcrumb(),
            $viewUrl => $this->getBreadcrumb(),
            __('Print invoice'),
        ];

        return $breadcrumbs;
    }

    public function getTitle(): string|Htmlable
    {
        return 'Invoice #'.$this->getRecord()->code;
    }
}
