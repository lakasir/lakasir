<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\About
 */
class AboutResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'shop_name' => $this?->shop_name ?? '',
            'shop_location' => $this?->shop_location ?? '',
            'owner_name' => $this?->tenantUser?->full_name ?? '',
            'business_type' => $this?->business_type ?? '',
            'currency' => $this?->currency ?? '',
            'photo_url' => $this?->photo ?? '',
        ];
    }
}
