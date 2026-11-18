<?php

namespace App\Http\Controllers\Api\Tenants;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProfileResource;
use App\Models\Tenants\UploadedFile;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
            'email' => ['nullable', 'email', 'unique:users,email,' . auth()->id()],
            'phone' => ['nullable', 'string', 'digits_between:10,13'],
            'address' => ['nullable', 'string'],
            'uploaded_file_id' => ['nullable', 'integer', 'exists:uploaded_files,id'],
        ]);

        try {
            DB::beginTransaction();
            /** @var \App\Models\Tenants\User $user */
            $user = auth()->user();
            $user->update($request->only('name', 'email'));

            /** @var \App\Models\Tenants\Profile $profile */
            $profile = $user->profile;
            $profile = $user->profile()->updateOrCreate([
                'user_id' => $user->id,
            ], $request->only('phone', 'address', 'locale'));

            if ($request->filled('uploaded_file_id')) {
                $tmpFile = UploadedFile::find($request->uploaded_file_id);

                if ($tmpFile && $tmpFile->relative_path !== $profile->photo) {
                    $relativePath = $tmpFile->moveToPublic('profile', $profile->photo ?: null);
                    $profile->update([
                        'photo' => $relativePath,
                    ]);
                }
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