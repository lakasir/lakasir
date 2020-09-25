<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\Master\CustomerType\BulkDelete;
use App\Http\Requests\Master\CustomerType\Update;
use App\Http\Requests\Master\CustomerType\Store;
use App\Http\Requests\Master\CustomerType\Index;
use App\Repositories\CustomerType as CustomerTypeRepository;
use App\Traits\HasCrudActions;
use Illuminate\Http\Request;

class CustomerType extends Controller
{
    use HasCrudActions;

    protected $viewPath = 'app.master.customer_types';

    protected $permission = 'customer_type';

    protected $indexRequest = Index::class;

    protected $storeRequest = Store::class;

    protected $updateRequest = Update::class;

    protected $bulkDestroyRequest = BulkDelete::class;

    protected $redirect = '/master/customer_type';

    protected $repositoryClass = CustomerTypeRepository::class;
}
