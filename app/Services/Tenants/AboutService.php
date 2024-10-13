<?php

namespace App\Services\Tenants;

use App\Models\Tenants\About;
use App\Models\Tenants\Setting;
use App\Models\Tenants\UploadedFile;
use App\Models\Tenants\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AboutService
{
    public function createOrUpdate(array $data): void
    {
        $idAbout = About::latest()->first()!== null?About::latest()->first()->getKey():1;

        $about = About::query()
            ->updateOrCreate([
                'id' => $idAbout,
            ], Arr::only($data, [
                'shop_name',
                'shop_location',
                'business_type',
                'other_business_type',
            ]));

        $owner = User::owner()->first();
        if ($owner && isset($data['owner_name'])) {
            $owner->name = $data['owner_name'];
            $owner->save();
        }

        if (isset($data['photo_url']) && $data['photo_url'] !== $about->photo) {
            /** @var \App\Models\Tenants\UploadedFile $tmpFile */
            $tmpFile = UploadedFile::where('url', $data['photo_url'])->first();
            $url = $data['photo_url'];
            if ($tmpFile) {
                $url = $tmpFile->moveToPuplic('profile', $about->photo ? Str::of($about->photo)->after('profile/') : null);
            }
            $about->update([
                'photo' => $url,
            ]);
        }

        if (! isset($data['photo_url'])) {
            /** @var \App\Models\Tenants\UploadedFile $tmpFile */
            $tmpFile = UploadedFile::where('url', $about->photo)->first();
            if ($tmpFile) {
                $tmpFile->deleteFromPublic('');
            } else {
                $path = parse_url($about->photo, PHP_URL_PATH); // Get the path from the URL
                $path = str(ltrim($path, '/'))->remove('storage');
                $exists = optional(Storage::disk('public'))->has($path);
                if ($exists) {
                    Storage::disk('public')->delete($path);
                }
            }

            $about->update([
                'photo' => null,
            ]);
        }
    }
}
