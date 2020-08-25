<?php

namespace App\Http\Controllers\Api\Transaction;

use App\Facades\Response;
use App\Http\Controllers\Controller;
use App\Repositories\Selling as SellingRepository;
use App\Services\SellingService;
use App\Http\Requests\Transaction\Selling\Index;
use App\Http\Requests\Transaction\Selling\Store;
use App\Traits\HasCrudActions;
use Illuminate\Http\JsonResponse;

/**
 * TODO: create resources of selling <sheenazien8 2020-08-24>
 * 1. List Of item
 * 2. Add To Cart by local storage
 * 3. Search by name, code
 * 4. get detail for automatic insert by scan barcode
 * 5. Cart List by local storage aja
 * 6. Create Submit Order Action
 */

class Selling extends Controller
{
    use HasCrudActions;

    protected $permission = 'selling';

    protected $indexRequest = Index::class;

    protected $storeRequest = Store::class;

    protected $repositoryClass = SellingRepository::class;

    protected $storeService = [SellingService::class, 'create'];

    protected $indexService = [SellingService::class, 'list_item'];

    protected $return =  'api';
}
