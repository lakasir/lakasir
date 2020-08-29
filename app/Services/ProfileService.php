<?php

namespace App\Services;

use App\Repositories\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Service For Complect Logic which related with Profile
 */
class ProfileService
{
    /**
     * @var ProfileRepository
     */
    private $profile;

    /**
     * @param ProfileRepository $profile
     */
    public function __construct()
    {
        $this->profile = new Profile();
    }

    public function create(Request $request)
    {
        try {
            $self = $this;
            return DB::transaction(static function () use ($request, $self) {
                $user = auth()->user();
                if ($user->profile) {
                    $profile = $self->profile->hasParent('user_id', $user)
                          ->update($request, $user->profile);
                    if ($request->photo_profile) {
                        $profile->deleteMedia($user->profile->media->first());
                    }
                    $profile->createMediaFromFile($request->photo_profile);
                }
                if (!$user->profile) {
                    $self->profile->hasParent('user_id', $user)->create($request)->createMediaFromFile($request->photo_profile);
                }
                return $user;
            });
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return abort(500, $e->getMessage());
        }
    }
}
