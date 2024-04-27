<?php

namespace App\Services\Tenants;

use App\Models\Tenants\About;
use App\Models\Tenants\UploadedFile;
use App\Models\Tenants\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class AboutService
{
    public function createOrUpdate(array $data): void
    {
        $about = About::query()
            ->createOrFirst(Arr::only($data, [
                'shop_name',
                'shop_location',
                'business_type',
            ]));

        $owner = User::owner()->first();
        if ($owner && isset($data['owner_name'])) {
            $owner->name = $data['owner_name'];
            $owner->save();
        }

        if (isset($data['photo_url']) && $data['photo_url'] !== $about->photo) {
            /** @var \App\Models\Tenants\UploadedFile $tmpFile */
            $tmpFile = UploadedFile::where('url', $data['photo_url'])->first();
            $url = $tmpFile->moveToPuplic('profile', $about->photo ? Str::of($about->photo)->after('profile/') : null);
            $about->update([
                'photo' => $url,
            ]);
        }
    }
}
