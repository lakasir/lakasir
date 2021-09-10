<?php

namespace App\Http\Controllers\User;

use App\DataTables\UserDataTable;
use App\Http\Requests\User\BulkDelete;
use App\Http\Requests\User\Browse;
use App\Http\Requests\User\Store;
use App\Http\Requests\User\Destroy;
use App\Http\Requests\User\Update;
use App\Models\Role;
use App\Models\User as UserModel;
use App\Services\User as UserService;
use App\Traits\User\UserTrait;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\View\View;

class UserController
{
    use UserTrait;

    private $viewPath = 'app.user';

    /**
     * @param Browse $request
     * @param UserDataTable $dataTable
     * @return mixed
     * @throws BindingResolutionException
     */
    public function index(Browse $request, UserDataTable $dataTable)
    {
        return $dataTable->render("{$this->viewPath}.index", [
            'resources' => $this->resources(),
            'roles' => Role::toBase()->get()->map(function ($c) {
              return ['id' => $c->name, 'text' => $c->name];
            })
        ]);
    }

    /**
     * @param Store $request
     * @return View
     * @throws BindingResolutionException
     */
    public function create(Store $request): View
    {
        return view("{$this->viewPath}.create", [
            'resources' => $this->resources()
        ]);
    }

    /**
     * @param Store $request
     * @param UserService $userService
     * @return RedirectResponse
     * @throws BindingResolutionException
     */
    public function store(Store $request, UserService $userService)
    {
        $userService->create($request);

        $message = __('app.global.message.success.create', [
            'item' => ucfirst($this->resources())
        ]);

        flash()->success($message);

        return redirect()->to(route("{$this->resources()}.index"));
    }

    /**
     * @param UserModel $user
     * @param Browse $request
     * @return View|Factory
     * @throws BindingResolutionException
     */
    public function show(UserModel $user, Browse $request)
    {
        return view("{$this->viewPath}.show", [
            'resources' => $this->resources(),
            'data' => $user
        ]);
    }

    /**
     * @param UserModel $user
     * @param Update $request
     * @return View|Factory
     * @throws BindingResolutionException
     */
    public function edit(UserModel $user, Update $request)
    {
        $data = $user;

        return view("{$this->viewPath}.edit", [
            'resources' => $this->resources(),
            'data' => $data
        ]);
    }

    /**
     * @param UserModel $user
     * @param Update $request
     * @param UserService $userService
     * @return RedirectResponse
     * @throws AuthorizationException
     * @throws BindingResolutionException
     */
    public function update(UserModel $user, UserService $userService, Update $request)
    {
        $userService->update($request, $user);

        $message = __('app.global.message.success.update', [
            'item' => ucfirst($this->resources())
        ]);

        flash()->success($message);

        return redirect()->to(route("{$this->resources()}.index"));
    }

    /**
     * @param UserModel $user
     * @param Destroy $request
     * @return RedirectResponse
     * @throws BindingResolutionException
     */
    public function destroy(UserModel $user, Destroy $request)
    {
        $user->delete();

        $message = __('app.global.message.success.delete', [
            'item' => ucfirst($this->resources())
        ]);

        flash()->success($message);

        return redirect()->to(route("{$this->resources()}.index"));
    }

    /**
     * @param BulkDelete $request
     * @param UserService $userService
     * @throws BindingResolutionException
     * @throws AuthorizationException
     */
    public function bulkDestroy(BulkDelete $request, UserService $userService)
    {
        $message = __('app.global.message.success.bulk-delete', [
            'item' => ucfirst($this->resources()),
            'count' => $userService->bulkDestroy($request)
        ]);

        flash()->success($message);

        return redirect()->to(route("{$this->resources()}.index"));
    }
}
