<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\Controller;
use App\Http\Requests\Transaction\Purchasing\BulkDelete;
use App\Http\Requests\Transaction\Purchasing\Index;
use App\Http\Requests\Transaction\Purchasing\Store;
use App\Http\Requests\Transaction\Purchasing\Update;
use App\Models\Purchasing as PurchasingModel;
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

    public function index()
    {
        get_lang();

        $request = resolve($this->indexRequest);

        if ($this->permission) {
            $this->authorize("browse-$this->permission");
        }

        if ($request->ajax() || isset($this->return) && $this->return == 'api') {
            return $this->repository->datatable($request);
        }

        $resources = $this->permission;

        if (isset($this->resources)) {
            $resources = $this->resources;
        }

        return view("{$this->viewPath}.index", [
            'resources' => $resources,
            'spending' => $this->repository->card()
        ]);
    }

    public function updatePaid(PurchasingModel $purchasing)
    {
        $this->authorize('update-paid-purchasing');

        $purchasing->update([
            'is_paid' => $purchasing->is_paid ? false : true
        ]);

        $message = __('app.global.message.update').' '. ucfirst($this->permission);

        flash()->success(dash_to_space($message));

        return redirect()->back();
    }
}
