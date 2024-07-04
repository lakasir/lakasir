<?php

namespace App\Http\Requests\Tenants\Master;

use App\Features\ProductExpired;
use App\Features\ProductStock;
use App\Models\Tenants\Category;
use App\Models\Tenants\Product;
use App\Models\Tenants\ProductImage;
use App\Services\Tenants\ProductService;
use Exception;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ProductRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules(): array
    {
        if ($this->method() == 'DELETE') {
            return [];
        }

        if (! feature(ProductStock::class)) {
            $this->merge([
                'is_non_stock' => true,
            ]);
        }

        if ($this->get('is_non_stock', false)) {
            $this->merge([
                'stock' => 0,
            ]);
        }

        if ($this->method() == 'PUT') {
            $product = Product::findorfail($this->route('product'));
            $this->merge([
                'sku' => $this->filled('sku') ? $this->sku : $product->sku,
                'barcode' => $this->filled('barcode') ? $this->barcode : $product->barcode,
                'name' => $this->filled('name') ? $this->name : $product->name,
                'category' => $this->filled('category') ? $this->category : $product->category_id,
                'stock' => $this->filled('stock') ? $this->stock : $product->stock,
                'initial_price' => $this->filled('initial_price') ? $this->initial_price : $product->initial_price,
                'selling_price' => $this->filled('selling_price') ? $this->selling_price : $product->selling_price,
                'type' => $this->filled('type') ? $this->type : $product->type,
                'hero_images_url' => $this->filled('hero_images_url') ? $this->hero_images_url : $product->hero_images[0] ?? '',
                'is_non_stock' => $this->filled('is_non_stock') ? $this->is_non_stock : $product->is_non_stock,
            ]);
        }

        return [
            'sku' => [Rule::unique(Product::class)->ignore($this->route('product'))],
            'barcode' => ['nullable', 'min:3', Rule::unique(Product::class)->ignore($this->route('product'))],
            'name' => ['required', 'min:3'],
            'category' => ['required'],
            'stock' => ['numeric', Rule::requiredIf(! $this->is_non_stock)],
            'initial_price' => ['numeric', 'required', 'lte:selling_price'],
            'selling_price' => ['numeric', 'required', 'gte:initial_price'],
            'type' => [Rule::in('product', 'service'), 'required'],
            'hero_images_url' => ['string'],
            'is_non_stock' => ['boolean', 'required'],
            'expired' => [
                Rule::requiredIf(function () {
                    return feature(ProductExpired::class) && $this->method() == 'POST';
                }),
                $this->method() == 'POST' ? [
                    'date',
                    'after_or_equal:now',
                ] : [],
            ],
        ];
    }

    public function created(): void
    {
        try {
            DB::beginTransaction();
            $product = new Product();
            $product->fill($this->merging());
            $product->save();
            $this->uploadImage($product);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function updated(): void
    {
        try {
            DB::beginTransaction();
            $product = Product::findorfail($this->route('product'));
            $product->fill($this->merging());
            $product->update();
            $this->uploadImage($product);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    private function merging(): array
    {
        return $this->merge([
            'category_id' => Category::findorfail($this->category)->id,
        ])->except('category', 'images');
    }

    private function images(): ?array
    {
        return $this->images;
    }

    private function uploadImage(Product $product): void
    {
        if ($this->filled('hero_images_url') && $this->hero_images_url != ($product->hero_images[0] ?? '')) {
            $productService = new ProductService();
            $heroImages = $productService->proceedUploadImage(Str::of($this->hero_images_url)->explode(',')->toArray(), $product);
            $product->hero_images = $heroImages;
            $product->save();
        }
    }

    public function deleteImages(): void
    {
        $product = $this->route('product');
        $images = ProductImage::where('product_id', $product->id)->get();
        foreach ($images as $image) {
            if (Storage::disk('public')->exists($image->name)) {
                Storage::disk('public')->delete($image->name);
            }
            $image->delete();
        }
    }
}
