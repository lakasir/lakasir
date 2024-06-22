<?php

namespace App\Http\Resources;

use App\Models\Tenants\Selling;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Selling
 */
class SellingCollection extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'member_id' => $this->member_id,
            'user_id' => $this->user_id,
            'code' => $this->code,
            'payment_method_id' => $this->payment_method_id,
            'payed_money' => $this->payed_money,
            'money_changes' => $this->money_changes,
            'total_price' => $this->total_price,
            'grand_total_price' => $this->grand_total_price,
            'total_cost' => $this->total_cost,
            'discount' => $this->discount_price,
            'discount_price' => $this->total_price - $this->discount_price,
            'total_discount_per_item' => $this->total_discount_per_item,
            'total_discount' => $this->discount_price + $this->total_discount_per_item,
            'friend_price' => $this->friend_price,
            'tax' => $this->tax,
            'tax_price' => $this->tax_price,
            'total_qty' => $this->total_qty,
            'note' => $this->note,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'member' => $this->whenLoaded('member'),
            'payment_method' => $this->whenLoaded('paymentMethod'),
            'selling_details' => SellingDetailCollection::collection($this->whenLoaded('sellingDetails')),
            'cashier' => $this->whenLoaded('user'),
        ];
    }
}
