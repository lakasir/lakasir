<?php

namespace App\Http\Controllers\Api\Master;

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

    protected $return =  'api';

    protected $permission = 'payment_method';

    protected $indexRequest = Index::class;

    protected $repositoryClass = PaymentMethodRepository::class;
}
