<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Laravel\Pennant\Feature;

/** @mixin \App\Models\Tenants\User */
class ProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $uploadDisk = config('filesystems.upload_disk');
        $photoPath = $this->profile?->photo;
        if ($photoPath && Str::startsWith($photoPath, ['http://', 'https://'])) {
            $photoUrl = $photoPath;
        } else {
            $photoUrl = $photoPath ? Storage::disk($uploadDisk)->url($photoPath) : null;
        }

        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->profile?->phone,
            'photo' => $photoUrl,
            'photo_path' => $photoPath,
            'address' => $this->profile?->address,
            'locale' => $this->profile?->locale,
            'roles' => $this->roles->first()->name,
            'permissions' => $this->getAllPermissions()->pluck('name'),
            'features' => Feature::all(),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}