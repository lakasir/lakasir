<?php

namespace App\Http\Controllers\Master;

use App\DataTables\GroupDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Master\Group\Browse;
use App\Http\Requests\Master\Group\BulkDelete;
use App\Http\Requests\Master\Group\Create;
use App\Http\Requests\Master\Group\Destroy;
use App\Http\Requests\Master\Group\Update;
use App\Traits\Group\GroupTrait;
use App\Models\Group as GroupModel;

class Group extends Controller
{
    use GroupTrait;

    private $viewPath = 'app.master.groups';

    /**
     * Display a listing of the resource.
     *
     * @param Browse $request
     * @param GroupDataTable $dataTable
     * @return mix
     */
    public function index(Browse $request, GroupDataTable $dataTable)
    {
        return $dataTable->render("{$this->viewPath}.index", [
            'resources' => $this->resources()
        ]);
    }

    /**
     * @param Create $request
     * @return View
     * @throws BindingResolutionException
     */
    public function create(Create $request): View
    {
        return view("{$this->viewPath}.create", [
            'resources' => $this->resources()
        ]);
    }

    /**
     * @param Create $request
     * @return RedirectResponse
     * @throws BindingResolutionException
     */
    public function store(Create $request)
    {
        GroupModel::create($request->all());

        $message = __('app.global.message.success.create', [
            'item' => ucfirst($this->resources())
        ]);

        flash()->success($message);

        return redirect()->to(route("{$this->resources()}.index"));
    }

    /**
     * @param GroupModel $customerType
     * @param Browse $request
     * @return View|Factory
     * @throws BindingResolutionException
     */
    public function show(GroupModel $customerType, Browse $request)
    {
        $data = $customerType;

        return view("{$this->viewPath}.show", [
            'resources' => $this->resources(),
            'data' => $data
        ]);
    }

    /**
     * @param GroupModel $customerType
     * @param ItemRepository $customerTypeService
     * @param Update $request
     * @return View|Factory
     * @throws BindingResolutionException
     */
    public function edit(GroupModel $customerType, Update $request)
    {
        $data = $customerType;

        return view("{$this->viewPath}.edit", [
            'resources' => $this->resources(),
            'data' => $data
        ]);
    }

    /**
     * @param Update $request
     * @return RedirectResponse
     * @throws AuthorizationException
     * @throws BindingResolutionException
     */
    public function update(GroupModel $customerType, Update $request)
    {
        $customerType->update($request->all());

        $message = __('app.global.message.success.update', [
            'item' => ucfirst($this->resources())
        ]);

        flash()->success($message);

        return redirect()->to(route("{$this->resources()}.index"));
    }

    /**
     * @param GroupModel $customerType
     * @param Destroy $request
     * @return RedirectResponse
     * @throws BindingResolutionException
     */
    public function destroy(GroupModel $customerType, Destroy $request)
    {
        $customerType->delete();

        $message = __('app.global.message.success.delete', [
            'item' => ucfirst($this->resources())
        ]);

        flash()->success($message);

        return redirect()->to(route("{$this->resources()}.index"));
    }

    /**
     * @param BulkDelete $request
     * @param GroupService $customerTypeService
     * @return RedirectResponse
     * @throws BindingResolutionException
     * @throws AuthorizationException
     */
    public function bulkDestroy(BulkDelete $request, GroupService $customerTypeService)
    {
        $customerTypeService->bulkDestroy($request);

        $message = __('app.global.message.success.bulk-delete', [
            'item' => ucfirst($this->resources())
        ]);

        flash()->success($message);

        return redirect()->to(route("{$this->resources()}.index"));
    }


}
