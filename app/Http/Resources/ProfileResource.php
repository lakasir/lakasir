<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
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
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->profile?->phone,
            'photo' => $this->profile?->photo,
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
