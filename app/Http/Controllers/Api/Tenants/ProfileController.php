<?php

namespace App\Http\Controllers\Api\Tenants;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProfileResource;
use App\Models\Tenants\UploadedFile;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProfileController extends Controller
{
    public function index()
    {
        return $this->buildResponse()
            ->setData(new ProfileResource(auth()->user()))
            ->present();
    }

    public function update(Request $request)
    {
        $this->validate($request, [
            'name' => ['nullable', 'string'],
            'email' => ['nullable', 'email', 'unique:users,email,'.auth()->id()],
            'phone' => ['nullable', 'string', 'digits_between:10,13'],
            'address' => ['nullable', 'string'],
            'photo_url' => ['nullable', 'string', 'url', 'regex:/^(http)?s?:?(\/\/[^\']*\.(?:png|jpg|jpeg|gif|png|svg))$/'],
        ]);

        try {
            DB::beginTransaction();
            /** @var \App\Models\Tenants\User $user */
            $user = auth()->user();
            $user->update($request->only('name', 'email'));

            /** @var \App\Models\Tenants\Profile $profile */
            $profile = $user->profile;
            $request->merge([
                'photo' => $request->photo_url,
            ]);
            $profile = $user->profile()->updateOrCreate([
                'user_id' => $user->id,
            ], $request->only('phone', 'address', 'locale'));

            if ($request->filled('photo_url') && $request->photo_url !== $profile->photo) {
                /** @var \App\Models\Tenants\UploadedFile $tmpFile */
                $tmpFile = UploadedFile::where('url', $request->photo_url)->first();
                $url = $tmpFile->moveToPuplic('profile', $profile->photo ? Str::of($profile->photo)->after('profile/') : null);
                $profile->update([
                    'photo' => $url,
                ]);
            }
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();

            return $this->buildResponse()
                ->setCode($e->getCode() !== 0 ? $e->getCode() : 500)
                ->setMessage($e->getMessage())
                ->present();
        }

        return $this->buildResponse()
            ->setMessage('Profile updated successfully')
            ->present();
    }
}
