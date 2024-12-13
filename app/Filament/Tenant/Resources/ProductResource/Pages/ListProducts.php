<?php

namespace App\Filament\Tenant\Resources\ProductResource\Pages;

use App\Features\ProductImport;
use App\Filament\Tenant\Resources\ProductResource;
use App\Imports\ProductImport as ImportsProductImport;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Resources\Pages\ListRecords;
use Maatwebsite\Excel\Facades\Excel;

class ListProducts extends ListRecords
{
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Action::make('import-product')
                ->label(__('Import product'))
                ->color('gray')
                ->visible(feature(ProductImport::class))
                ->form([
                    FileUpload::make('attachment')
                        ->acceptedFileTypes(['application/vnd.ms-excel', 'text/csv']),
                ])->action(function (array $data) {
                    $file = public_path('storage/'.$data['attachment']);

                    Excel::import(new ImportsProductImport, $file);
                }),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return '/member/products';
    }
}
