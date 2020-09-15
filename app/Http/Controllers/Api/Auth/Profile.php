<?php

namespace App\Http\Controllers\Api\Auth;

use App\Repositories\User;
use App\Traits\HasCrudActions;
use App\Services\ProfileService;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\Profile\Index;
use App\Http\Requests\User\Profile\Store;

class Profile extends Controller
{
    use HasCrudActions;

    protected $return =  'api';

    protected $permission =  null;

    protected $indexRequest = Index::class;

    protected $storeRequest = Store::class;

    protected $repositoryClass = User::class;

    protected $indexService = [ ProfileService::class, 'getProfile' ];

    protected $storeService = [ ProfileService::class, 'create' ];
}
