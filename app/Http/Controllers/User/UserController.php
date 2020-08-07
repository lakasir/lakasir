<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\BulkDelete;
use App\Http\Requests\User\Index;
use App\Http\Requests\User\Store;
use App\Http\Requests\User\Update;
use App\Repositories\User as UserRepository;
use App\Services\UserService;
use App\Traits\HasCrudActions;

class UserController extends Controller
{
    use HasCrudActions;

    protected $viewPath = 'app.user';

    protected $permission = 'user';

    protected $indexRequest = Index::class;

    protected $storeRequest = Store::class;

    protected $updateRequest = Update::class;

    protected $bulkDestroyRequest = BulkDelete::class;

    protected $redirect = '/user';

    protected $repositoryClass = UserRepository::class;

    protected $indexService = [UserService::class, 'index'];
}
