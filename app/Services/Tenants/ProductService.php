<?php

namespace App\Services\Tenants;

use App\Models\Tenants\Product;
use App\Models\Tenants\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductService
{
    public function proceedUploadImage(array $heroImages, Product $product): array
    {
        $uploadedHeroImages = [];
        $tmp = UploadedFile::whereIn('url', $heroImages)->get();
        /** @var UploadedFile $item */
        foreach ($tmp as $item) {
            $url = $item->moveToPuplic('product');
            $uploadedHeroImages[] = $url;
        }
        foreach ($product->hero_images as $image) {
            /** @var UploadedFile $uploadedFile */
            $uploadedFile = UploadedFile::where('url', $image)->first();
            $uploadedFile->deleteFromPublic('product');
        }

        return $uploadedHeroImages;
    }

    public function handleCreateUploadedFile(array $heroImages): array
    {
        $urls = [];
        foreach ($heroImages as $heroImage => $originalName) {
            $name = $heroImage;
            $url = optional(Storage::disk('public'))->url($name);
            $urls[] = $url;
            if (! UploadedFile::where('url', $url)->exists()) {
                UploadedFile::create([
                    'name' => Str::of($heroImage)->replace('product/', ''),
                    'original_name' => $originalName,
                    'url' => $url,
                    'mime_type' => optional(Storage::disk('public'))->mimeType($name),
                    'extension' => File::extension($name),
                    'size' => Storage::disk('public')->size($name),
                    'disk' => 'public',
                    'path' => storage_path('app/'.$name),
                ]);
            }
        }

        return $urls;
    }

    public function handleDeleteUploadedFile(array $heroImages): void
    {
        foreach ($heroImages as $heroImage) {
            $uploadedFile = UploadedFile::where('url', $heroImage)->first();
            $uploadedFile->deleteFromPublic('product');
            $uploadedFile->delete();
        }
    }
}
