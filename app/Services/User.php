<?php

namespace App\Services;

use App\Consntanta\UserVariable;
use Illuminate\Http\Request;
use App\Models\User as UserModel;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class User
{
    /**
     * @param Request $request
     * @return UserModel
     */
    public function create(Request $request): UserModel
    {
        $self = $this;
        return DB::transaction(static function () use ($request, $self) {
            if (!config('lakasir.installed')) {
                $session = $request->session()->all()['user'];
                $request->merge($session);
            }
            $request->merge(['password' => bcrypt($request->password)]);
            $user = new $self->model();
            $user = $user->fill($request->all());
            $user->save();
            $role_name = UserVariable::EMPLOYEE;
            if ($request->role) {
                $role_name = $request->role;
            }
            $role = Role::whereName($role_name)->first();
            $user->assignRole($role);

            return $user;
        });
    }

    /**
     * @param Request $request
     * @param UserModel $user
     * @return UserModel
     */
    public function update(Request $request, $user): UserModel
    {
        $self = $this;
        return DB::transaction(static function () use ($request, $self, $user) {
            if (!config('lakasir.installed')) {
                $session = $request->session()->all()['user'];
                $request->merge($session);
            }
            if ($request->password) {
                $request->merge(['password' => bcrypt($request->password)]);
            } else {
                $request->merge(['password' => $user->password]);
            }
            $user = $user->fill($request->all());
            $user->save();
            if ($request->role) {
                $self->role = $request->role;
                $role = Role::whereName($self->role)->first();
                $user->syncRoles($role);
            }

            return $user;
        });
    }

    /**
     * @param Request $request
     * @param UserModel $user
     * @return UserModel
     */
    public function updateProfile(Request $request, $user): UserModel
    {
        $self = $this;
        return DB::transaction(static function () use ($request, $self, $user) {
            $auth = $self->update($request, $user);
            $auth->profile()->updateOrCreate([], $request->only(['phone', 'bio', 'address', 'lang']));
            if ($request->file('photo_profile')) {
                /** @var \App\Models\Profile $profile */
                $profile = $auth->profile;
                $media = $auth->profile->media;
                if ($media->count() > 0) {
                    $profile->deleteMedia($media->first());
                }
                $profile->createMediaFromFile($request->file('photo_profile'));
            }

            return $auth;
        });
    }

    /**
     * @param Request $request
     * @param UserModel $user
     * @return mixed
     */
    public function updatePassword(Request $request, $user)
    {
        $request->merge(['password' => bcrypt($request->new_password)]);
        $user->update($request->only(['password']));

        return $user;
    }


    /**
     * @param string $role
     * @return User
     */
    public function role(string $role): self
    {
        $this->role = $role;
        return $this;
    }
}
