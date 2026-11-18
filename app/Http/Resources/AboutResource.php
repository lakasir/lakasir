<?php

namespace App\Http\Resources;

use App\Models\Tenants\Setting;
use App\Models\Tenants\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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
        $owner = User::owner()->first();
        $uploadDisk = config('filesystems.upload_disk');
        $photo = $this?->photo;
        if ($photo && Str::startsWith($photo, ['http://', 'https://'])) {
            $photoUrl = $photo;
        } else {
            $photoUrl = $photo ? Storage::disk($uploadDisk)->url($photo) : '';
        }

        return [
            'shop_name' => $this?->shop_name ?? '',
            'shop_location' => $this?->shop_location ?? '',
            'owner_name' => $owner->name ?? '',
            'business_type' => $this?->business_type ?? '',
            'other_business_type' => $this?->other_business_type ?? '',
            'currency' => Setting::get('currency') ?? 'IDR',
            'photo_url' => $photoUrl,
            'photo_path' => $this?->photo ?? '',
        ];
    }
}