<?php

namespace App\Filament\Tenant\Resources\ProductResource\Pages;

use App\Features\ProductImport;
use App\Filament\Imports\ProductImporter;
use App\Filament\Tenant\Resources\ProductResource;
use Filament\Actions;
use Filament\Actions\ImportAction;
use Filament\Resources\Pages\ListRecords;

class ListProducts extends ListRecords
{
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            ImportAction::make()
                ->label(__('Import product'))
                ->translateLabel()
                ->visible(feature(ProductImport::class))
                ->importer(ProductImporter::class),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return '/member/products';
    }
}
