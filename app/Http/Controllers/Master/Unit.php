<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\Master\Unit\BulkDelete;
use App\Http\Requests\Master\Unit\Index;
use App\Http\Requests\Master\Unit\Store;
use App\Http\Requests\Master\Unit\Update;
use App\Repositories\Unit as UnitRepository;
use App\Traits\HasCrudActions;

class Unit extends Controller
{
    use HasCrudActions;

    protected $viewPath = 'app.master.units';

    protected $permission = 'unit';

    protected $indexRequest = Index::class;

    protected $storeRequest = Store::class;

    protected $updateRequest = Update::class;

    protected $bulkDestroyRequest = BulkDelete::class;

    protected $redirect = '/master/unit';

    protected $repositoryClass = UnitRepository::class;
}
