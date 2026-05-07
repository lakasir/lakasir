<?php

namespace App\Services\Tenants;

use App\Models\Tenants\Product;
use App\Models\Tenants\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ProductService
{
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
