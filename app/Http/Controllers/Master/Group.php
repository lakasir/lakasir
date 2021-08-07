<?php

namespace App\Http\Controllers\Master;

use App\DataTables\GroupDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Master\Group\Browse;
use App\Http\Requests\Master\Group\BulkDelete;
use App\Http\Requests\Master\Group\Create;
use App\Http\Requests\Master\Group\Destroy;
use App\Http\Requests\Master\Group\Update;
use App\Models\Customer;
use App\Traits\Group\GroupTrait;
use App\Models\Group as GroupModel;
use App\Services\Group as GroupService;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

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
            'resources' => $this->resources(),
            'customers' => Customer::select('id', 'name')->get()
        ]);
    }

    /**
     * @param Create $request
     * @return RedirectResponse
     * @throws BindingResolutionException
     */
    public function store(Create $request, GroupService $groupService)
    {
        try {
            $groupService->create($request);

            $message = __('app.global.message.success.create', [
                'item' => ucfirst($this->resources())
            ]);

            flash()->success($message);

        } catch (Exception $e) {
            $message = __('app.global.message.error.create', [
                'item' => ucfirst($this->resources())
            ]);

            flash()->error($message);
        }

        return redirect()->to(route("{$this->resources()}.index"));
    }

    /**
     * @param GroupModel $group
     * @param Browse $request
     * @return View|Factory
     * @throws BindingResolutionException
     */
    public function show(GroupModel $group, Browse $request)
    {
        $data = $group;

        return view("{$this->viewPath}.show", [
            'resources' => $this->resources(),
            'data' => $data
        ]);
    }

    /**
     * @param GroupModel $group
     * @param ItemRepository $groupService
     * @param Update $request
     * @return View|Factory
     * @throws BindingResolutionException
     */
    public function edit(GroupModel $group, Update $request)
    {
        $data = $group;
        $selected_customer = $data->customers->pluck('id')->toArray();

        return view("{$this->viewPath}.edit", [
            'resources' => $this->resources(),
            'data' => $data,
            'selected_customer' => $selected_customer,
            'customers' => Customer::select('id', 'name')->get()
        ]);
    }

    /**
     * @param GroupModel $group
     * @param Update $request
     * @param GroupService $groupService
     * @return RedirectResponse
     */
    public function update(GroupModel $group, Update $request, GroupService $groupService)
    {
         try {
             $groupService->update($request, $group);

             $message = __('app.global.message.success.update', [
                 'item' => ucfirst($this->resources())
             ]);

             flash()->success($message);
        } catch (Exception $e) {
            DB::rollBack();
            $message = __('app.global.message.error.update', [
                'item' => ucfirst($this->resources())
            ]);

            flash()->error($message);
        }

         return redirect()->to(route("{$this->resources()}.index"));
    }

    /**
     * @param GroupModel $group
     * @param Destroy $request
     * @return RedirectResponse
     * @throws BindingResolutionException
     */
    public function destroy(GroupModel $group, Destroy $request)
    {
        $group->delete();

        $message = __('app.global.message.success.delete', [
            'item' => ucfirst($this->resources())
        ]);

        flash()->success($message);

        return redirect()->to(route("{$this->resources()}.index"));
    }

    /**
     * @param BulkDelete $request
     * @param GroupService $groupService
     * @return RedirectResponse
     */
    public function bulkDestroy(BulkDelete $request, GroupService $groupService)
    {
        try {
            $groupService->bulkDestroy($request);

            $message = __('app.global.message.success.bulk-delete', [
                'item' => ucfirst($this->resources())
            ]);

            flash()->success($message);

        } catch (Exception $e) {
            $message = __('app.global.message.error.bulk-delete', [
                'item' => ucfirst($this->resources())
            ]);

            flash()->error($message);
        }

        return redirect()->to(route("{$this->resources()}.index"));
    }
}
