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
}
