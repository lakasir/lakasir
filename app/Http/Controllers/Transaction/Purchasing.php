<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\Controller;
use App\Http\Requests\Transaction\Purchasing\BulkDelete;
use App\Http\Requests\Transaction\Purchasing\Index;
use App\Http\Requests\Transaction\Purchasing\Store;
use App\Http\Requests\Transaction\Purchasing\Update;
use App\Repositories\Item;
use App\Repositories\PaymentMethod;
use App\Repositories\Purchasing as PurchasingRepository;
use App\Repositories\Supplier;
use App\Services\PurchasingService;
use App\Traits\HasCrudActions;
use Illuminate\View\View;

class Purchasing extends Controller
{
    use HasCrudActions;

    protected $viewPath = 'app.transaction.purchasings';

    protected $permission = 'purchasing';

    protected $indexRequest = Index::class;

    protected $storeRequest = Store::class;

    protected $updateRequest = Update::class;

    protected $bulkDestroyRequest = BulkDelete::class;

    protected $redirect = '/transaction/purchasing';

    protected $repositoryClass = PurchasingRepository::class;

    protected $storeService = [PurchasingService::class, 'create'];
}
