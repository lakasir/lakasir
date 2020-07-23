<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\Master\Customer\BulkDelete;
use App\Http\Requests\Master\Customer\Index;
use App\Http\Requests\Master\Customer\Store;
use App\Http\Requests\Master\Customer\Update;
use App\Repositories\Customer as CustomerRepository;
use App\Traits\HasCrudActions;

class Customer extends Controller
{
    use HasCrudActions;

    protected $viewPath = 'app.master.customers';

    protected $permission = 'customer';

    protected $indexRequest = Index::class;

    protected $storeRequest = Store::class;

    protected $updateRequest = Update::class;

    protected $bulkDestroyRequest = BulkDelete::class;

    protected $redirect = '/master/customer';

    protected $repositoryClass = CustomerRepository::class;
}
