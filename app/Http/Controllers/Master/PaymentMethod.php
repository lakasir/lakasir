<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\Master\PaymentMethod\BulkDelete;
use App\Http\Requests\Master\PaymentMethod\Update;
use App\Http\Requests\Master\PaymentMethod\Store;
use App\Http\Requests\Master\PaymentMethod\Index;
use App\Repositories\PaymentMethod as PaymentMethodRepository;
use App\Traits\HasCrudActions;
use Illuminate\Http\Request;

class PaymentMethod extends Controller
{
    use HasCrudActions;

    protected $viewPath = 'app.master.payment_methods';

    protected $permission = 'payment_method';

    protected $indexRequest = Index::class;

    protected $storeRequest = Store::class;

    protected $updateRequest = Update::class;

    protected $bulkDestroyRequest = BulkDelete::class;

    protected $redirect = '/master/payment_method';

    protected $repositoryClass = PaymentMethodRepository::class;
}
