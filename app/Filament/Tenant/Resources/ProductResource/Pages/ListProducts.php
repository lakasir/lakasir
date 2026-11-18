<?php

namespace App\Filament\Tenant\Resources\ProductResource\Pages;

use App\Features\ProductImport;
use App\Filament\Tenant\Resources\ProductResource;
use App\Imports\ProductImport as ImportsProductImport;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Storage;
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
                        ->disk(config('filesystems.upload_disk'))
                        ->acceptedFileTypes(['application/vnd.ms-excel', 'text/csv']),
                ])->action(function (array $data) {
                    $uploadDisk = config('filesystems.upload_disk');
                    $filePath = $data['attachment'];
                    $driver = config('filesystems.disks.' . $uploadDisk . '.driver');

                    if ($driver === 'local' || $driver === 'public') {
                        $fullPath = Storage::disk($uploadDisk)->path($filePath);
                        Excel::import(new ImportsProductImport, $fullPath);
                    } else {
                        $tmpPath = tempnam(sys_get_temp_dir(), 'lakasir_import_');
                        try {
                            file_put_contents($tmpPath, Storage::disk($uploadDisk)->get($filePath));
                            Excel::import(new ImportsProductImport, $tmpPath);
                        } finally {
                            @unlink($tmpPath);
                        }
                    }
                }),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return '/member/products';
    }
}