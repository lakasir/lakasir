<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\Role\BulkDelete;
use App\Http\Requests\User\Role\Index;
use App\Http\Requests\User\Role\Store;
use App\Http\Requests\User\Role\Update;
use App\Repositories\Role as RoleRepository;
use App\Services\RoleService;
use App\Traits\HasCrudActions;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Spatie\Permission\Models\Permission;

class Role extends Controller
{
    use HasCrudActions;

    protected $viewPath = 'app.user.role';

    protected $permission = 'role';

    protected $indexRequest = Index::class;

    protected $storeRequest = Store::class;

    protected $updateRequest = Update::class;

    protected $bulkDestroyRequest = BulkDelete::class;

    protected $redirect = 'user/role';

    protected $repositoryClass = RoleRepository::class;

    protected $storeService = [RoleService::class, 'create'];

    protected $updateService = [RoleService::class, 'update'];

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create(): View
    {
        get_lang();

        $this->authorize("create-$this->permission");
        $permissions = Permission::toBase()->get()->map(function ($c, $i) {
            $name = str_replace('-', ' ', Str::title($c->name));
            $explode = Str::of($name)->explode(' ')->last();
            return [
                'id' => $c->id,
                'text' => $name,
                'header' => $explode
            ];
        });
        $permissions = $permissions->groupBy('header');

        return view("{$this->viewPath}.create", compact('permissions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function edit(int $model): View
    {
        get_lang();

        $data = $this->repository->find($model);

        $this->authorize("update-$this->permission");

        $permissions = Permission::toBase()->get()->map(function ($c, $i) {
            $name = str_replace('-', ' ', Str::title($c->name));
            $explode = Str::of($name)->explode(' ')->last();
            return [
                'id' => $c->id,
                'text' => $name,
                'header' => $explode
            ];
        });
        $permissions = $permissions->groupBy('header');

        return view("{$this->viewPath}.edit", compact('permissions', 'data'));
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

        $this->authorize("delete-{$this->permission}");

        $data = $this->repository->find($model);

        if ($data->id == 1) {
            $message = __('app.role.message.error.delete_owner');

            flash()->error(dash_to_space($message));

            return redirect()->to($this->redirect);
        }

        $data->delete();

        $message = __('app.global.message.delete').' '. ucfirst($this->permission);

        flash()->success(dash_to_space($message));

        return redirect()->to($this->redirect);
    }


}
