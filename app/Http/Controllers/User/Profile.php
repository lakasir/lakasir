<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\Profile\Index;
use App\Http\Requests\User\Profile\Store;
use App\Repositories\Profile as ProfileRepository;
use App\Services\ProfileService;
use App\Traits\HasCrudActions;

class Profile extends Controller
{
    use HasCrudActions;

    protected $viewPath = 'app.user.profiles';

    protected $permission = 'profile';

    protected $redirect = '/user/profile';

    protected $storeRequest = Store::class;

    protected $indexRequest = Index::class;

    protected $repositoryClass = ProfileRepository::class;

    protected $storeService = [ProfileService::class, 'create'];
}
