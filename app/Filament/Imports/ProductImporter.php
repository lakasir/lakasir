<?php

namespace App\Filament\Imports;

use App\Models\Tenants\Category;
use App\Models\Tenants\Product;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Filament\Forms\Components\Checkbox;

class ProductImporter extends Importer
{
    protected static ?string $model = Product::class;

    private Category $category;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('category')
                ->relationship(resolveUsing: function (string $state): Category {
                    return Category::query()->updateOrCreate(
                        [
                            'name' => $state,
                        ],
                        [
                            'name' => $state,
                        ],
                    );
                })
                ->label(__('Category'))
                ->example('UMUM/makanan/minuman')
                ->rules(['string', 'min:3']),
            ImportColumn::make('name')
                ->label(__('Name'))
                ->example('Susu UHT')
                ->rules(['string', 'min:3']),
            ImportColumn::make('unit')
                ->label(__('Unit'))
                ->example('pcs')
                ->rules(['string', 'min:3']),
            ImportColumn::make('barcode')
                ->label(__('Barcode'))
                ->example('11111')
                ->rules(['string', 'min:3']),
            ImportColumn::make('stock')
                ->label(__('Stock'))
                ->example('1000')
                ->rules(['numeric']),
            // TODO: fix the expired
            // ImportColumn::make('expired')
            //     ->label(__('Expired'))
            //     ->example('2024-12-31')
            //     ->rules(['string']),
            ImportColumn::make('initial_price')
                ->label(__('Inital price'))
                ->example('2200')
                ->rules(['numeric']),
            ImportColumn::make('selling_price')
                ->label(__('Selling price'))
                ->example('2500')
                ->rules(['numeric']),
            ImportColumn::make('type')
                ->label(__('Type'))
                ->example('product/services')
                ->rules(['string']),
        ];
    }

    public static function getOptionsFormComponents(): array
    {
        return [
            Checkbox::make('updateExisting')
                ->label(__('Update existing records')),
        ];
    }

    public function resolveRecord(): ?Product
    {
        $data = $this->data;

        $category = Category::query()->updateOrCreate(
            [
                'name' => $data['category'],
            ],
            [
                'name' => $data['category'],
            ],
        );

        return Product::query()
            ->firstOrNew(
                [
                    'name' => $data['name'],
                ], [
                    'name' => $data['name'],
                    'category_id' => $category->id,
                    'unit' => $data['unit'],
                    'barcode' => $data['barcode'],
                    'stock' => $data['stock'],
                    'initial_price' => $data['initial_price'],
                    'selling_price' => $data['selling_price'],
                    'type' => $data['type'] ?? 'product',
                ],
            );
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your product import has completed and '.number_format($import->successful_rows).' '.str('row')->plural($import->successful_rows).' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' '.number_format($failedRowsCount).' '.str('row')->plural($failedRowsCount).' failed to import.';
        }

        return $body;
    }
}
