<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StockCollection extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'product' => $this->product->name,
            'stock' => $this->stock,
            'init_stock' => $this->init_stock,
            'initial_price' => $this->initial_price,
            'selling_price' => $this->selling_price,
            'type' => $this->type,
            'date' => $this->date,
        ];
    }
}
