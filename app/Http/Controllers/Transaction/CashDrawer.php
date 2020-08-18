<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\Controller;
use App\Http\Requests\Transaction\CashDrawer\Store;
use App\Repositories\CashDrawer as CashDrawerRepository;
use Illuminate\View\View;

class CashDrawer extends Controller
{
    protected $viewPath = 'app.transaction.cashdrawers';

    protected $permission = 'selling';

    protected $storeRequest = Store::class;

    protected $redirect = '/transaction/selling';

    protected $repositoryClass = CashDrawerRepository::class;

    private $repository;

    /**
     * @param
     */
    public function __construct()
    {
        $this->repository = new $this->repositoryClass();
    }


    public function open()
    {
        get_lang();

        $request = resolve($this->storeRequest);

        if ($this->permission) {
            $this->authorize("create-$this->permission");
        }

        $request->merge([
            'today' => today()->format('Y-m-d'),
        ]);

        $user = auth()->user();

        $this->repository->hasParent('user_id', $user)->create($request);

        flash()->success(__('app.global.message.success'). __('app.cashdrawers.message.open'));

        return redirect()->to($this->redirect);
    }

    public function close()
    {
        get_lang();

        $request = resolve($this->storeRequest);

        if ($this->permission) {
            $this->authorize("create-$this->permission");
        }

        $request->merge([
            'today' => today()->format('Y-m-d'),
            'status' => false
        ]);

        $user = auth()->user();

        $this->repository->hasParent('user_id', $user)->create($request);

        flash()->success(__('app.global.message.success'). __('app.cashdrawers.message.close'));

        return redirect()->to($this->redirect);
    }
}
