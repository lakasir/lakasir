<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SellingDetailCollection extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'selling_id' => $this->selling_id,
            'product_id' => $this->product_id,
            'qty' => $this->qty,
            'price' => $this->price,
            'total_price' => $this->total_price,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            // 'selling' => $this->whenLoaded('selling'),
            'product' => new ProductCollection($this->whenLoaded('product')),
        ];
    }
}
