<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\ChangePassword\Store;
use App\Repositories\User as UserRepository;
use App\Services\UserService;
use App\Traits\HasCrudActions;

class ChangePassword extends Controller
{
    use HasCrudActions;

    protected $viewPath = 'app.user.change_passwords';

    protected $permission = null;

    protected $redirect = '/user/change_password';

    protected $storeRequest = Store::class;

    protected $indexRequest = Index::class;

    protected $repositoryClass = UserRepository::class;

    protected $storeService = [UserService::class, 'updatePassword'];

    public function index()
    {
        return view("{$this->viewPath}.index");
    }
}
