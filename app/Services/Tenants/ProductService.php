<?php

namespace App\Services\Tenants;

use App\Models\Tenants\Category;
use App\Models\Tenants\Product;
use App\Models\Tenants\ProductImage;
use App\Models\Tenants\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductService
{
    /**
     * Create a product from validated input, and its primary barcode and image.
     *
     * @param  array<string, mixed>  $data
     */
    public function create(array $data): Product
    {
        return DB::transaction(function () use ($data) {
            $product = new Product();
            $product->fill($this->fillable($data));
            $product->save();
            $this->syncPrimaryBarcode($product, $data['barcode'] ?? null);
            $this->uploadImage($product, $data['hero_images_uploaded_file_id'] ?? null);

            return $product;
        });
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function update(Product $product, array $data): Product
    {
        return DB::transaction(function () use ($product, $data) {
            $product->fill($this->fillable($data));
            $product->update();
            $this->syncPrimaryBarcode($product, $data['barcode'] ?? null);
            $this->uploadImage($product, $data['hero_images_uploaded_file_id'] ?? null);

            return $product;
        });
    }

    /**
     * Remove a product with its images, so no file is left on the disk.
     */
    public function delete(Product $product): void
    {
        DB::transaction(function () use ($product) {
            $this->deleteImages($product);
            $product->delete();
        });
    }

    public function deleteImages(Product $product): void
    {
        $uploadDisk = config('filesystems.upload_disk');

        foreach (ProductImage::where('product_id', $product->id)->get() as $image) {
            if (Storage::disk($uploadDisk)->exists($image->name)) {
                Storage::disk($uploadDisk)->delete($image->name);
            }
            $image->delete();
        }
    }

    /**
     * The product columns, with the category resolved from its id.
     *
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    private function fillable(array $data): array
    {
        if (isset($data['category'])) {
            $data['category_id'] = Category::findOrFail($data['category'])->id;
        }

        return collect($data)
            ->except(['category', 'images', 'barcode', 'hero_images_uploaded_file_id'])
            ->all();
    }

    /**
     * Make $code the product's primary barcode, deactivating the previous one.
     */
    private function syncPrimaryBarcode(Product $product, mixed $code): void
    {
        if (empty($code)) {
            return;
        }

        // The observer's fallback barcode goes: the caller named this product's
        // code, and two primary codes make a scan ambiguous. Other codes stay
        // (deactivated), so a product can keep extra barcodes.
        $product->barcodes()
            ->where('code', '!=', $code)
            ->where('description', __('Auto-generated code'))
            ->delete();

        $product->barcodes()
            ->primary()
            ->active()
            ->where('code', '!=', $code)
            ->update(['is_active' => false]);

        $barcode = $product->barcodes()->where('code', $code)->first();
        if ($barcode) {
            $barcode->update(['type' => 'primary', 'is_active' => true]);

            return;
        }

        $product->barcodes()->create([
            'code' => $code,
            'type' => 'primary',
            'description' => __('Auto-generated code'),
            'is_active' => true,
        ]);
    }

    /**
     * Move an uploaded temp file onto the product as its hero image.
     */
    private function uploadImage(Product $product, mixed $uploadedFileId): void
    {
        if (empty($uploadedFileId)) {
            return;
        }

        $existingFile = UploadedFile::find($uploadedFileId);

        if ($existingFile && $existingFile->relative_path !== ($product->hero_images[0] ?? '')) {
            $product->hero_images = $this->proceedUploadImage([$uploadedFileId], $product);
            $product->save();
        }
    }

    /**
     * Process uploaded hero images: move from tmp to the upload disk.
     * Accepts an array of UploadedFile IDs (from the API upload endpoint).
     * Returns an array of relative paths.
     */
    public function proceedUploadImage(array $uploadedFileIds, Product $product): array
    {
        $uploadedHeroImages = [];

        $items = UploadedFile::whereIn('id', $uploadedFileIds)->get()->keyBy('id');

        foreach ($uploadedFileIds as $id) {
            $item = $items->get($id);
            if ($item) {
                $relativePath = $item->moveToPublic('product');
                $uploadedHeroImages[] = $relativePath;
            }
        }

        foreach ($product->hero_images as $image) {
            $uploadedFile = UploadedFile::where('relative_path', $image)->first()
                ?? UploadedFile::where('url', $image)->first();
            if ($uploadedFile) {
                $uploadedFile->deleteFromPublic('product');
            }
        }

        return $uploadedHeroImages;
    }

    /**
     * Handle creation of UploadedFile records for Filament-based uploads.
     * Files are already stored on the upload disk by Filament's FileUpload.
     * This stores relative paths (not full URLs).
     */
    public function handleCreateUploadedFile(array $heroImages): array
    {
        $uploadDisk = config('filesystems.upload_disk');
        $paths = [];

        foreach ($heroImages as $heroImage => $originalName) {
            $relativePath = $heroImage;

            if (! UploadedFile::where('relative_path', $relativePath)->exists()) {
                UploadedFile::create([
                    'name' => basename($relativePath),
                    'original_name' => $originalName,
                    'url' => Storage::disk($uploadDisk)->url($relativePath),
                    'mime_type' => Storage::disk($uploadDisk)->mimeType($relativePath),
                    'extension' => pathinfo($relativePath, PATHINFO_EXTENSION),
                    'size' => Storage::disk($uploadDisk)->size($relativePath),
                    'relative_path' => $relativePath,
                    'path' => '',
                    'disk' => $uploadDisk,
                ]);
            }

            $paths[] = $relativePath;
        }

        return $paths;
    }

    /**
     * Delete uploaded file records for the given hero images.
     */
    public function handleDeleteUploadedFile(array $heroImages): void
    {
        foreach ($heroImages as $heroImage) {
            $uploadedFile = UploadedFile::where('relative_path', $heroImage)->first()
                ?? UploadedFile::where('url', $heroImage)->first();

            if ($uploadedFile) {
                $uploadedFile->deleteFromPublic('product');
            }
        }
    }
}
