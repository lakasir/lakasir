<?php

namespace App\Http\Controllers\Api\Tenants;

use App\Http\Controllers\Controller;
use App\Http\Resources\AboutResource;
use App\Models\Tenants\UploadedFile;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AboutController extends Controller
{
    public function index()
    {
        return $this->buildResponse()
            ->setData(new AboutResource(tenant()->user->about))
            ->present();
    }

    public function update(Request $request)
    {
        $this->validate($request, [
            'shop_name' => ['nullable', 'string'],
            'shop_location' => ['nullable', 'string'],
            'business_type' => ['nullable', 'string', 'in:retail,wholesale'],
            'owner_name' => ['nullable', 'string'],
        ]);

        $about = tenant()->user->about()->updateOrCreate([
            'tenant_user_id' => tenant()->user->id,
        ], $request->only('shop_name', 'shop_location', 'business_type'));

        tenant()->user->update([
            'full_name' => $request->owner_name,
        ]);

        if ($request->filled('photo_url') && $request->photo_url !== $about->photo) {
            /** @var \App\Models\Tenants\UploadedFile $tmpFile */
            $tmpFile = UploadedFile::where('url', $request->photo_url)->first();
            $url = $tmpFile->moveToPuplic('profile', $about->photo ? Str::of($about->photo)->after('profile/') : null);
            $about->update([
                'photo' => $url,
            ]);
        }

        return $this->buildResponse()
            ->setMessage('About updated successfully')
            ->present();
    }
}
