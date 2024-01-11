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
            'hero_images' => $this->hero_images,
            'stocks' => $this->whenLoaded('stocks'),
        ];
    }
}