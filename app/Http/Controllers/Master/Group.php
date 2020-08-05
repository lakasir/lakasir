<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\Master\GroupReq\BulkDelete;
use App\Http\Requests\Master\GroupReq\Index;
use App\Http\Requests\Master\GroupReq\Store;
use App\Http\Requests\Master\GroupReq\Update;
use App\Repositories\Group as GroupRepository;
use App\Traits\HasCrudActions;

class Group extends Controller
{
    use HasCrudActions;

    protected $viewPath = 'app.master.groups';

    protected $permission = 'group';

    protected $indexRequest = Index::class;

    protected $storeRequest = Store::class;

    protected $updateRequest = Update::class;

    protected $bulkDestroyRequest = BulkDelete::class;

    protected $redirect = '/master/group';

    protected $repositoryClass = GroupRepository::class;
}
