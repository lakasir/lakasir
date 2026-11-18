<?php

namespace App\Imports;

use App\Models\Tenants\Category;
use App\Models\Tenants\PriceUnit;
use App\Models\Tenants\Product;
use App\Observers\ProductObserver;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProductImport implements SkipsEmptyRows, ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // WithHeadingRow converts blank headers to numeric keys (1, 2, etc.)
        // The Excel template's column B has a blank header for "name",
        // so it becomes key "1" instead of "name".
        // Map it properly:
        if (isset($row['name'])) {
            $name = $row['name'];
        } elseif (isset($row[1])) {
            // Column B (index 1 after heading row conversion) is the "name" column
            $name = $row[1];
            unset($row[1]);
        } else {
            return null; // Skip rows without a name
        }

        // Skip rows without a name value
        if (empty($name)) {
            return null;
        }

        $row['name'] = $name;

        // Ensure import category exists before product creation.
        $category = Category::query()->firstOrCreate(
            ['name' => $row['category'] ?? 'Uncategorized'],
            ['name' => $row['category'] ?? 'Uncategorized'],
        );

        if (!empty($row['barcode'])) {
            ProductObserver::setTempBarcodesData([
                ['code' => $row['barcode'], 'type' => 'primary', 'is_active' => true]
            ]);
        }

        /** @var Product $product */
        $product = Product::create([
            'name' => $row['name'],
            'category_id' => $category->id,
            'unit' => $row['unit'] ?? null,
            'sku' => $row['sku'] ?? null,
            'stock' => (int) ($row['stock'] ?? 0),
            'initial_price' => $row['initial_price'] ?? 0,
            'selling_price' => $row['selling_price'] ?? 0,
            'type' => !empty($row['type']) ? $row['type'] : 'product',
        ]);

        if (!empty($row['barcode'])) {
            $product->barcodes()->create([
                'code' => $row['barcode'],
                'type' => 'primary',
                'description' => 'Imported barcode',
                'is_active' => true,
            ]);
        }

        if (isset($row['other_price']) && $row['other_price'] != null && $row['other_price'] != '') {
            $dataOtherPrice = Str::of($row['other_price'])->explode(',');

            /** @var PriceUnit $priceUnit */
            $priceUnit = new PriceUnit();
            $priceUnit->fill([
                'selling_price' => $dataOtherPrice[0],
                'unit' => $dataOtherPrice[1],
                'stock' => $dataOtherPrice[2] ?? 1,
            ]);

            $priceUnit->product()->associate($product);

            $priceUnit->save();
        }

        return $product;
    }
}
