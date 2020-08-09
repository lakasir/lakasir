<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\Role\BulkDelete;
use App\Http\Requests\User\Role\Index;
use App\Http\Requests\User\Role\Store;
use App\Http\Requests\User\Role\Update;
use App\Repositories\Role as RoleRepository;
use App\Services\RoleService;
use App\Traits\HasCrudActions;

class Role extends Controller
{
    use HasCrudActions;

    protected $viewPath = 'app.user.role';

    protected $permission = 'role';

    protected $indexRequest = Index::class;

    protected $storeRequest = Store::class;

    protected $updateRequest = Update::class;

    protected $bulkDestroyRequest = BulkDelete::class;

    protected $redirect = '/user';

    protected $repositoryClass = RoleRepository::class;

    protected $storeService = [RoleService::class, 'store'];
}
