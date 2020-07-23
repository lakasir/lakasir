<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\Master\Supplier\BulkDelete;
use App\Http\Requests\Master\Supplier\Index;
use App\Http\Requests\Master\Supplier\Store;
use App\Http\Requests\Master\Supplier\Update;
use App\Repositories\Supplier as SupplierRepository;
use App\Traits\HasCrudActions;

class Supplier extends Controller
{
    use HasCrudActions;

    protected $viewPath = 'app.master.suppliers';

    protected $permission = 'supplier';

    protected $indexRequest = Index::class;

    protected $storeRequest = Store::class;

    protected $updateRequest = Update::class;

    protected $bulkDestroyRequest = BulkDelete::class;

    protected $redirect = '/master/supplier';

    protected $repositoryClass = SupplierRepository::class;
}
