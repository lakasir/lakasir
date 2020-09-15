<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\Controller;
use App\Http\Requests\Transaction\Purchasing\Index;
use App\Repositories\Purchasing as PurchasingRepository;
use App\Traits\HasCrudActions;

class BillPurchasing extends Controller
{
    use HasCrudActions;

    protected $viewPath = 'app.transaction.purchasings';

    protected $permission = 'purchasing';

    protected $indexRequest = Index::class;

    protected $redirect = '/transaction/bill_purchasing';

    protected $repositoryClass = PurchasingRepository::class;

    protected $storeService = [PurchasingService::class, 'create'];
}
