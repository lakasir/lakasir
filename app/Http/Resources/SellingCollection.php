<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SellingCollection extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'member_id' => $this->member_id,
            'code' => $this->code,
            'payment_method_id' => $this->payment_method_id,
            'payed_money' => $this->payed_money,
            'money_changes' => $this->money_changes,
            'total_price' => $this->total_price,
            'total_cost' => $this->total_cost,
            'friend_price' => $this->friend_price,
            'tax' => $this->tax,
            'total_qty' => $this->total_qty,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'member' => $this->whenLoaded('member'),
            'payment_method' => $this->whenLoaded('paymentMethod'),
            'selling_details' => SellingDetailCollection::collection($this->whenLoaded('sellingDetails')),
        ];
    }
}
