<?php

namespace App\Http\Requests\Master;

use App\Models\Category;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Product;
use App\Models\ProductImage;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        if($this->method() == 'DELETE') return [];
        return [
            "name" => ["required", "min:3"],
            "category" => ["required"],
            "stock" => ["numeric", "required", "min:0"],
            "initial_price" => ["numeric", "required", "lte:selling_price"],
            "selling_price" => ["numeric", "required", "gte:initial_price"],
            "type" => [Rule::in("product", "service"), "required"],
            "images" => ["array"],
            "images.name" => ["string"],
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

    public function updated()
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

    private function uploadImage(Product $product)
    {
        if (!is_null($this->images()) && count($this->images()) > 0) {
            $images = [];
            foreach ($this->images() as $image) {
                $tmp = Storage::disk('tmp');
                if (!$tmp->exists($image['name'])) {
                    throw new Exception("file in temp dir is not found");
                }
                $pathSource = $tmp->path($image['name']);
                $destinationPath = Storage::disk('public');
                $destinationPath->putFileAs('', $pathSource, $image['name']);
                $images[] = [
                    "product_id" => $product->id,
                    "name" => $image['name'],
                    "url" => $destinationPath->url($image['name']),
                    "created_at" => now(),
                ];
            }
            ProductImage::insert($images);
        }
    }

    public function deleteImages()
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