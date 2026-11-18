<?php

use App\Imports\ProductImport;
use App\Models\Tenants\Barcode;
use App\Models\Tenants\Category;
use App\Models\Tenants\Product;
use Tests\RefreshDatabaseWithTenant;

uses(RefreshDatabaseWithTenant::class);

beforeEach(function () {
    Category::factory()->create(['name' => 'Uncategorized']);
});

describe('ProductImport barcode handling', function () {
    test('import with barcode creates exactly 1 barcode with correct code', function () {
        $import = new ProductImport();
        $product = $import->model([
            'name' => 'Test Product',
            'barcode' => 'IMP-001',
            'category' => 'Uncategorized',
            'unit' => 'pcs',
            'stock' => 10,
            'initial_price' => 10000,
            'selling_price' => 15000,
            'type' => 'product',
        ]);

        expect($product)->toBeInstanceOf(Product::class);

        $barcodes = $product->barcodes()->get();
        expect($barcodes)->toHaveCount(1);
        expect($barcodes->first()->code)->toBe('IMP-001');
        expect($barcodes->first()->type)->toBe('primary');
        expect($barcodes->first()->is_active)->toBeTrue();
    });

    test('import without barcode auto-generates one barcode', function () {
        $import = new ProductImport();
        $product = $import->model([
            'name' => 'Test Product',
            'category' => 'Uncategorized',
            'unit' => 'pcs',
            'stock' => 10,
            'initial_price' => 10000,
            'selling_price' => 15000,
            'type' => 'product',
        ]);

        expect($product)->toBeInstanceOf(Product::class);

        $barcodes = $product->barcodes()->get();
        expect($barcodes)->toHaveCount(1);
        expect($barcodes->first()->type)->toBe('primary');
    });

    test('import with empty barcode string still auto-generates one barcode', function () {
        $import = new ProductImport();
        $product = $import->model([
            'name' => 'Test Product',
            'barcode' => '',
            'category' => 'Uncategorized',
            'unit' => 'pcs',
            'stock' => 10,
            'initial_price' => 10000,
            'selling_price' => 15000,
            'type' => 'product',
        ]);

        expect($product)->toBeInstanceOf(Product::class);

        $barcodes = $product->barcodes()->get();
        expect($barcodes)->toHaveCount(1);
        expect($barcodes->first()->type)->toBe('primary');
    });
});
