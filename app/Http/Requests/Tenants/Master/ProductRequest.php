<?php

namespace App\Http\Requests\Tenants\Master;

use App\Features\ProductExpired;
use App\Features\ProductStock;
use App\Models\Tenants\Category;
use App\Models\Tenants\Product;
use App\Models\Tenants\ProductImage;
use App\Models\Tenants\UploadedFile;
use App\Models\Tenants\Barcode;
use App\Services\Tenants\ProductService;
use Exception;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
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
                'barcode' => $this->filled('barcode') ? $this->barcode : $product->barcodes()->primary()->active()->value('code'),
                'name' => $this->filled('name') ? $this->name : $product->name,
                'category' => $this->filled('category') ? $this->category : $product->category_id,
                'stock' => $this->filled('stock') ? $this->stock : $product->stock,
                'initial_price' => $this->filled('initial_price') ? $this->initial_price : $product->initial_price,
                'selling_price' => $this->filled('selling_price') ? $this->selling_price : $product->selling_price,
                'type' => $this->filled('type') ? $this->type : $product->type,
                'hero_images_uploaded_file_id' => $this->filled('hero_images_uploaded_file_id') ? $this->hero_images_uploaded_file_id : null,
                'is_non_stock' => $this->filled('is_non_stock') ? $this->is_non_stock : $product->is_non_stock,
            ]);
        }

        return [
            'sku' => [Rule::unique(Product::class)->ignore($this->route('product'))],
            'barcode' => ['nullable', 'min:3', function ($attribute, $value, $fail) {
                if ($value) {
                    $query = Barcode::where('code', $value);
                    if ($this->method() == 'PUT') {
                        $product = Product::findorfail($this->route('product'));
                        $query->where('product_id', '!=', $product->id);
                    }
                    if ($query->exists()) {
                        $fail('The barcode has already been taken.');
                    }
                }
            }],
            'name' => ['required', 'min:3'],
            'category' => ['required'],
            'stock' => ['numeric', Rule::requiredIf(! $this->is_non_stock)],
            'initial_price' => ['numeric', 'required', 'lte:selling_price'],
            'selling_price' => ['numeric', 'required', 'gte:initial_price'],
            'type' => [Rule::in('product', 'service'), 'required'],
            'hero_images_uploaded_file_id' => ['nullable', 'integer', 'exists:uploaded_files,id'],
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
            $this->syncPrimaryBarcode($product);
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
            $this->syncPrimaryBarcode($product);
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
        ])->except('category', 'images', 'barcode');
    }

    private function syncPrimaryBarcode(Product $product): void
    {
        if (! $this->filled('barcode')) {
            return;
        }

        $barcodeCode = $this->barcode;
        $product->barcodes()
            ->primary()
            ->active()
            ->where('code', '!=', $barcodeCode)
            ->update(['is_active' => false]);

        $barcode = $product->barcodes()->where('code', $barcodeCode)->first();
        if ($barcode) {
            $barcode->update([
                'type' => 'primary',
                'is_active' => true,
            ]);

            return;
        }

        $product->barcodes()->create([
            'code' => $barcodeCode,
            'type' => 'primary',
            'description' => 'API barcode',
            'is_active' => true,
        ]);
    }

    private function images(): ?array
    {
        return $this->images;
    }

    private function uploadImage(Product $product): void
    {
        if ($this->filled('hero_images_uploaded_file_id')) {
            $uploadedFileId = $this->hero_images_uploaded_file_id;

            $existingFile = UploadedFile::find($uploadedFileId);

            if ($existingFile && $existingFile->relative_path !== ($product->hero_images[0] ?? '')) {
                $productService = new ProductService();
                $heroImages = $productService->proceedUploadImage([$uploadedFileId], $product);
                $product->hero_images = $heroImages;
                $product->save();
            }
        }
    }

    public function deleteImages(): void
    {
        $uploadDisk = config('filesystems.upload_disk');
        $product = $this->route('product');
        $images = ProductImage::where('product_id', $product->id)->get();
        foreach ($images as $image) {
            if (Storage::disk($uploadDisk)->exists($image->name)) {
                Storage::disk($uploadDisk)->delete($image->name);
            }
            $image->delete();
        }
    }
}
