<?php

Namespace App\Repositories;

use App\Abstracts\Repository as RepositoryAbstract;
use Illuminate\Http\Request;

class User extends RepositoryAbstract
{
    protected string $model = 'App\Models\User';

    public function create(Request $request)
    {
        $session = $request->session()->all()['user'];
        $request->merge($session);
        $request->merge(['password' => bcrypt($request->password)]);
        $user = new $this->model();
        $user = $user->fill($request->all());
        $user->save();

        return $user;
    }
}

