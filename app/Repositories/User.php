<?php

Namespace App\Repositories;

use App\Abstracts\Repository as RepositoryAbstract;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class User extends RepositoryAbstract
{
    protected string $model = 'App\Models\User';

    /**
     * @var string
     */
    private string $role = 'employee';


    public function create(Request $request)
    {
        $self = $this;
        return DB::transaction(static function() use ($request, $self) {
            $session = $request->session()->all()['user'];
            $request->merge($session);
            $request->merge(['password' => bcrypt($request->password)]);
            $user = new $self->model();
            $user = $user->fill($request->all());
            $user->save();
            $role = Role::whereName($self->role)->first();
            $user->assignRole($role);

            return $user;
        });
    }

    public function role(string $role): self
    {
        $this->role = $role;
        return $this;
    }


}

