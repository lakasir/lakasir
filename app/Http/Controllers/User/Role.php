<?php

namespace App\Http\Controllers\User;

use App\DataTables\RoleDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\Role\BulkDelete;
use App\Http\Requests\User\Role\Browse;
use App\Http\Requests\User\Role\Create;
use App\Http\Requests\User\Role\Update;
use App\Models\Role as RoleModel;
use App\Repositories\Role as RoleRepository;
use App\Services\RoleService;
use App\Traits\RoleTrait;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Sheenazien8\Hascrudactions\Traits\HasCrudAction;
use Spatie\Permission\Models\Permission;

class Role
{
    use RoleTrait;

    protected $viewPath = 'app.user.role';

    /**
     * @param Browse $request
     * @param RoleDataTable $dataTable
     * @return mixed
     * @throws BindingResolutionException
     */
    public function index(Browse $request, RoleDataTable $dataTable)
    {
        return $dataTable->render("{$this->viewPath}.index", [
            'resources' => $this->resources(),
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
     * @param RoleService $roleService
     * @return RedirectResponse
     * @throws BindingResolutionException
     */
    public function store(Create $request, RoleService $roleService)
    {
        $roleService->create($request);

        $message = __('app.global.message.success.create', [
            'item' => ucfirst($this->resources())
        ]);

        flash()->success($message);

        return redirect()->to(route("{$this->resources()}.index"));
    }

    /**
     * @param RoleModel $role
     * @param Browse $request
     * @return View|Factory
     * @throws BindingResolutionException
     */
    public function show(RoleModel $role, Browse $request)
    {
        return view("{$this->viewPath}.show", [
            'resources' => $this->resources(),
            'data' => $role
        ]);
    }

    /**
     * @param RoleModel $role
     * @param Update $request
     * @return View|Factory
     * @throws BindingResolutionException
     */
    public function edit(RoleModel $role, Update $request)
    {
        $data = $role;

        return view("{$this->viewPath}.edit", [
            'resources' => $this->resources(),
            'data' => $data
        ]);
    }

    /**
     * @param RoleModel $role
     * @param Update $request
     * @param RoleService $roleService
     * @return RedirectResponse
     * @throws AuthorizationException
     * @throws BindingResolutionException
     */
    public function update(RoleModel $role, RoleService $roleService, Update $request)
    {
        $roleService->update($request, $role);

        $message = __('app.global.message.success.update', [
            'item' => ucfirst($this->resources())
        ]);

        flash()->success($message);

        return redirect()->to(route("{$this->resources()}.index"));
    }

    /**
     * @param RoleModel $role
     * @param Destroy $request
     * @return RedirectResponse
     * @throws BindingResolutionException
     */
    public function destroy(RoleModel $role, Destroy $request)
    {
        $role->delete();

        $message = __('app.global.message.success.delete', [
            'item' => ucfirst($this->resources())
        ]);

        flash()->success($message);

        return redirect()->to(route("{$this->resources()}.index"));
    }

    /**
     * @param BulkDelete $request
     * @param RoleService $roleService
     * @throws BindingResolutionException
     * @throws AuthorizationException
     */
    public function bulkDestroy(BulkDelete $request, RoleService $roleService)
    {
        $message = __('app.global.message.success.bulk-delete', [
            'item' => ucfirst($this->resources()),
            'count' => $roleService->bulkDestroy($request)
        ]);

        flash()->success($message);

        return redirect()->to(route("{$this->resources()}.index"));
    }
}
