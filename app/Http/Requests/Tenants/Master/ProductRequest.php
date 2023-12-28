<?php

namespace App\Http\Requests\Tenants\Master;

use App\Models\Tenants\Category;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Tenants\Product;
use App\Models\Tenants\ProductImage;
use App\Models\Tenants\UploadedFile;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules(): array
    {
        if($this->method() == 'DELETE') return [];
        return [
            "name" => ["required", "min:3"],
            "category" => ["required"],
            "stock" => ["numeric", "required", "min:0"],
            "initial_price" => ["numeric", "required", "lte:selling_price"],
            "selling_price" => ["numeric", "required", "gte:initial_price"],
            "type" => [Rule::in("product", "service"), "required"],
            "hero_images_url" => ["string"],
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
            "category_id" => Category::findorfail($this->category)->id
        ])->except('category', 'images');
    }

    private function images(): array|null
    {
        return $this->images;
    }

    private function uploadImage(Product $product): void
    {
        $heroImages = [];
        if ($this->filled('hero_images_url')) {
            $tmp = UploadedFile::whereIn('url', Str::of($this->hero_images_url)->explode(','))->get();
            /** @var UploadedFile $item */
            foreach ($tmp as $item) {
                $url = $item->moveToPuplic('product');
                $heroImages[] = $url;
            }
            foreach ($product->hero_images as $image) {
                /** @var UploadedFile $uploadedFile */
                $uploadedFile = UploadedFile::where('url', $image)->first();
                $uploadedFile->deleteFromPublic('product');
            }
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
