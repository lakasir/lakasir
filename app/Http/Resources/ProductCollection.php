<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductCollection extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'category' => $this->category,
            'category_id' => $this->category->id,
            'initial_price' => $this->initial_price,
            'selling_price' => $this->selling_price,
            'type' => $this->type,
            'unit' => $this->unit,
            'stock' => $this->stock,
            'is_non_stock' => (bool) $this->is_non_stock,
            'hero_images' => $this->hero_images,
            'sku' => $this->sku,
            'barcode' => $this->barcode,
            'show' => $this->show,
            'stocks' => $this->whenLoaded('stocks'),
        ];
    }
}
