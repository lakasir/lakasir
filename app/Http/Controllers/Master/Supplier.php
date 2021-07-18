<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\Master\Supplier\BulkDelete;
use App\Http\Requests\Master\Supplier\Index;
use App\Http\Requests\Master\Supplier\Store;
use App\Http\Requests\Master\Supplier\Update;
use App\Repositories\Supplier as SupplierRepository;
use Sheenazien8\Hascrudactions\Traits\HasCrudAction;

class Supplier extends Controller
{
    use HasCrudAction;

    protected $viewPath = 'app.master.suppliers';

    protected $permission = 'supplier';

    protected $indexRequest = Index::class;

    protected $storeRequest = Store::class;

    protected $updateRequest = Update::class;

    protected $bulkDestroyRequest = BulkDelete::class;

    protected $resources = 'supplier';

    protected $repositoryClass = SupplierRepository::class;
}
