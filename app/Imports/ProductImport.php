<?php

namespace App\Imports;

use App\Models\Tenants\Category;
use App\Models\Tenants\PriceUnit;
use App\Models\Tenants\Product;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProductImport implements SkipsEmptyRows, ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        $category = Category::query()->updateOrCreate(
            [
                'name' => $row['category'],
            ],
            [
                'name' => $row['category'],
            ],
        );

        /** @var Product $product */
        $product = Product::create([
            'name' => $row['name'],
            'category_id' => $category->id,
            'unit' => $row['unit'],
            'barcode' => (int) $row['barcode'],
            'stock' => (int) $row['stock'],
            'initial_price' => $row['initial_price'] ?? 0,
            'selling_price' => $row['selling_price'] ?? 0,
            'type' => $row['type'] ?? 'product',
        ]);

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
