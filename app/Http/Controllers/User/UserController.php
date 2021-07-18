<?php

namespace App\Http\Controllers\User;

use App\Http\Requests\User\BulkDelete;
use App\Http\Requests\User\Index;
use App\Http\Requests\User\Store;
use App\Http\Requests\User\Update;
use App\Repositories\User as UserRepository;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;
use Sheenazien8\Hascrudactions\Abstracts\LaTable;
use InvalidArgumentException;
use Spatie\Permission\Models\Role;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/** @package App\Http\Controllers\User */
class UserController
{
    private $viewPath = 'app.user';

    private $permission = 'user';

    private $resources = 'user';

    /**
     * @param UserRepository $userRepository
     * @param Index $request
     * @return View|Factory|LaTable
     * @throws InvalidArgumentException
     * @throws BindingResolutionException
     */
    public function index(UserRepository $userRepository, Index $request)
    {
        if (isset($this->permission)) {
            if ($this->permission && $request->type != 'select2') {
                Gate::authorize("browse-{$this->permission}");
            }
        }

        if ($request->ajax() || isset($this->return) && $this->return == 'api') {
            return $userRepository->datatable($request);
        }

        $resources = except_last_word($request->route()->getName());

        if (isset($this->resources)) {
            $resources = $this->resources;
        }

        $data = [
            'resources' => $resources,
        ];

        if (config('lakasir.index-style') == 'grid') {
            $data = array_merge($data, [
                'users' => $userRepository->query()->paginate()
            ]);
        }

        return view("{$this->viewPath}.index", $data);
    }

    /**
     * @return View
     * @throws BindingResolutionException
     */
    public function create(): View
    {
        Gate::authorize("create-$this->permission");

        $roles = Role::toBase()->get()->map(function ($c) {
            return ['id' => $c->name, 'text' => $c->name];
        });

        return view("{$this->viewPath}.create", compact('roles'));
    }

    /**
     * @param Store $request
     * @param UserRepository $userRepository
     * @return RedirectResponse
     */
    public function store(Store $request, UserRepository $userRepository)
    {
        if (isset($this->permission)) {
            Gate::authorize("create-$this->permission");
        }

        $userRepository->create($request);

        $resources = except_last_word(request()->route()->getName());

        $message = __('hascrudactions::app.global.message.success.create', [
            'item' => ucfirst($resources ?? '')
        ]);

        if (isset($this->resources)) {
            $resources = $this->resources;
        }

        return redirect()->to(route($this->redirect ?? $resources . '.index'))->with('message', [
            'success' => dash_to_space($message)
        ]);
    }

    /**
     * @param Update $request
     * @param UserRepository $userRepository
     * @param mixed $model
     * @return RedirectResponse
     * @throws ModelNotFoundException
     */
    public function update(Update $request, UserRepository $userRepository, $model)
    {
        if (isset($this->permission)) {
            Gate::authorize("update-{$this->permission}");
        }

        $data = $userRepository->find($model);

        $data = $userRepository->update($request, $data);

        if (isset($this->resources)) {
            $resources = $this->resources;
        }

        $message = __('hascrudactions::app.global.message.success.update', [
            'item' => ucfirst($resources ?? '')
        ]);

        return redirect()->to(route($this->redirect ?? $resources . '.index'))->with('message', [
            'success' => dash_to_space($message)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function edit(int $model): View
    {
        $data = $this->repository->find($model);

        Gate::authorize("update-$this->permission");

        $roles = Role::toBase()->get()->map(function ($c) {
            return ['id' => $c->name, 'text' => $c->name];
        });

        return view("{$this->viewPath}.edit", compact('roles', 'data'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $model
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $model): RedirectResponse
    {
        get_lang();

        Gate::authorize("delete-{$this->permission}");

        $data = $this->repository->find($model);

        $response = Gate::inspect("can-delete-{$this->permission}", $data);

        if (!$response->allowed()) {
            flash()->error(trans('app.user.message.delete.error'));

            return redirect()->to(route($this->resources . '.index'));
        }

        $data->delete();

        $message = __('app.global.message.delete') . ' ' . ucfirst($this->permission);

        flash()->success(dash_to_space($message));

        return redirect()->to(route($this->resources . '.index'));
    }

    /**
     * @param BulkDelete $request
     * @param UserRepository $userRepository
     * @return RedirectResponse
     * @throws BindingResolutionException
     * @throws HttpException
     * @throws NotFoundHttpException
     */
    public function bulkDestroy(BulkDelete $request, UserRepository $userRepository)
    {
        if (isset($this->resources)) {
            $resources = $this->resources;
        }

        $message = __('hascrudactions::app.global.message.fail.delete', [
            'item' => ucfirst($resources ?? '')
        ]);

        if (!request()->ids) {
            return redirect()->to(route($resources . '.index'))->with('message', [
                'error' => dash_to_space($message)
            ]);
        }

        if (isset($this->permission)) {
            Gate::authorize("create-$this->permission");
        }

        $userRepository->bulkDestroy($request);

        $message = __('hascrudactions::app.global.message.success.delete', [
            'item' => ucfirst($resources ?? '')
        ]);

        return redirect()->to(route($this->redirect ?? $resources . '.index'))->with('message', [
            'success' => dash_to_space($message)
        ]);
    }
}
