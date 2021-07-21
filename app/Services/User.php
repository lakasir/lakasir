<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\Models\User as UserModel;
use Illuminate\Support\Facades\DB;
use Sheenazien8\Hascrudactions\Abstracts\Repository as AbstractsRepository;
use Spatie\Permission\Models\Role;

class User extends AbstractsRepository
{
    /**
     * @param
     */
    public function __construct()
    {
        parent::__construct(new UserModel());
    }


    /**
     * @var string
     */
    private string $role = 'employee';

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
            if ($request->role) {
                $self->role = $request->role;
            }
            $role = Role::whereName($self->role)->first();
            $user->assignRole($role);

            return $user;
        });
    }

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
            }
            $role = Role::whereName($self->role)->first();
            $user->syncRoles($role);

            return $user;
        });
    }

    public function updatePassword(Request $request, $user)
    {
        $user->update($request->only(['password']));

        return $user;
    }


    public function role(string $role): self
    {
        $this->role = $role;
        return $this;
    }
}
