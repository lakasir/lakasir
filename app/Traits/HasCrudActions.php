<?php

namespace App\Traits;

use App\Exceptions\ServiceActionsException;
use App\Facades\Response;
use App\Http\Requests\Master\Unit\Index;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Lakasir\UserLoggingActivity\Facades\Activity;
use Maatwebsite\Excel\Facades\Excel;

/**
 * Trait HasCrudActions
 * @author sheenazien
 */
trait HasCrudActions
{
    protected $repository;
    /**
     * @param repository
     */
    public function __construct()
    {
        $this->repository = new $this->repositoryClass();
    }

    /**
     * Display a listing of the resource.
     *
     * @return mix
     */
    public function index()
    {
        get_lang();

        $request = resolve($this->indexRequest);

        if ($this->permission) {
            $this->authorize("browse-$this->permission");
        }

        if ($request->ajax() || isset($this->return) && $this->return == 'api') {
            if (isset($this->indexService)) {
                if (count($this->indexService) > 2) {
                    throw new ServiceActionsException('Index Service property is cant to more 2 index');
                }
                if (!is_array($this->indexService)) {
                    throw new ServiceActionsException('Index Service property must be array');
                }
                $resources = ( new $this->indexService[0] )->{$this->indexService[1]}($request);

                if (isset($this->return) && $this->return == 'api') {
                    return Response::success($resources);
                }

                return $this->repository->getObjectModel()->table($resources);
            } else {
                if (isset($this->return) && $this->return == 'index') {
                    return;
                }
                if ($request->type == 'select2') {
                    $result = $this->repository->query()->select('id', $request->key)->when(
                        $request->term && $request->key,
                        function ($query) use ($request)
                        {
                            return $query->where($request->key, 'LIKE', '%%'.$request->term.'%%');
                        })->when(
                        $request->oldValue,
                        function ($query) use($request)
                        {
                            $str = ltrim($request->oldValue, '[');
                            $str = rtrim($str, ']');
                            $array = explode(',', $str);
                            if ($request->oldValue) {
                                if (is_array($array) && count($array) > 1) {
                                    return $query->whereIn('id', $array);
                                }
                                return $query->where('id', $request->oldValue);
                            }
                        })->when($request->filter, function ($query) use ($request)
                        {
                            return $query->where($request->filter['key'], $request->filter['value']);
                        })->get()->toArray();

                    return Response::success($result);
                }

                if (isset($this->return) && $this->return == 'api') {
                    $result = $this->repository->query()->get()->toArray();

                    return Response::success($result);
                }

                return $this->repository->datatable($request);
            }
        }

        $resources = $this->permission;

        if (isset($this->resources)) {
            $resources = $this->resources;
        }

        return view("{$this->viewPath}.index", [
            'resources' => $resources
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create(): View
    {
        get_lang();

        $this->authorize("create-$this->permission");

        return view("{$this->viewPath}.create");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return mix
     */
    public function store()
    {
        get_lang();

        $request = resolve($this->storeRequest);

        if ($this->permission) {
            $this->authorize("create-$this->permission");
        }

        if (isset($this->storeService)) {
            if (count($this->storeService) > 2) {
                throw new ServiceActionsException('Store Service property is cant to more 2 index');
            }
            if (!is_array($this->storeService)) {
                throw new ServiceActionsException('Store Service property must be array');
            }
            $data = ( new $this->storeService[0] )->{$this->storeService[1]}($request);
        } else {
            $data = $this->repository->create($request);
        }
        $message = __('app.global.message.create').' '. ucfirst($this->permission);

        if (method_exists($data, 'logs')) {
            Activity::modelable($data)->auth()->creating();
        }

        if (isset($this->return) && $this->return == 'api') {
            return Response::success($data);
        }

        flash()->success(dash_to_space($message));


        return redirect()->to($this->redirect);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $model
     * @return mix
     */
    public function show(int $model)
    {
        get_lang();

        $this->authorize("browse-{$this->permission}");

        $data = $this->repository->find($model);

        if (request()->ajax() || isset($this->return) && $this->return == 'api') {
            if (isset($this->showService)) {

                if (count($this->showService) > 2) {
                    throw new ServiceActionsException('Index Service property is cant to more 2 show');
                }
                if (!is_array($this->showService)) {
                    throw new ServiceActionsException('Index Service property must be array');
                }
                $resources = ( new $this->showService[0] )->{$this->showService[1]}($data);

                if (isset($this->return) && $this->return == 'api') {
                    return Response::success($resources);
                }

                return Response::success($resources);
            }
            /* $data = $this->repository->find($model); */

            return Response::success($data->toArray());
        }

        /* $data = $this->repository->find($model); */

        return view("{$this->viewPath}.show", compact('data'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $model
     * @return \Illuminate\View\View
     */
    public function edit(int $model)
    {
        get_lang();

        $data = $this->repository->find($model);

        $this->authorize("update-{$this->permission}");

        return view("{$this->viewPath}.edit", compact('data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int $model
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(int $model): RedirectResponse
    {
        get_lang();

        $data = $this->repository->find($model);

        $request = resolve($this->updateRequest);

        if ($this->permission) {
            $this->authorize("update-{$this->permission}");
        }

        if (isset($this->updateService)) {
            if (count($this->updateService) > 2) {
                throw new ServiceActionsException('Store Service property is cant to more 2 index');
            }
            if (!is_array($this->updateService)) {
                throw new ServiceActionsException('Store Service property must be array');
            }
            $data = (new $this->updateService[0])->{$this->updateService[1]}($data, $request);
        } else {
            $data = $this->repository->update($request, $data);
        }

        $message = __('app.global.message.update').' '. ucfirst($this->permission);

        if (isset($this->return) && $this->return == 'api') {
            return response()->json($data, 200);
        }

        if (method_exists($data, 'logs')) {
            Activity::modelable($data)->auth()->updating();
        }

        flash()->success(dash_to_space($message));

        return redirect()->to($this->redirect);
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

        if (method_exists($data, 'logs')) {
            Activity::sync()->modelable($data)->auth()->deleting();
        }

        $data->delete();

        $message = __('app.global.message.delete').' '. ucfirst($this->permission);

        flash()->success(dash_to_space($message));

        return redirect()->to($this->redirect);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function bulkDestroy(): RedirectResponse
    {
        get_lang();

        $request = resolve($this->bulkDestroyRequest);

        $this->repository->bulkDestroy($request);

        $message = __('app.global.message.delete').' '. ucfirst($this->permission);

        flash()->success(dash_to_space($message));

        return redirect()->back();
    }

    public function downloadTemplate()
    {
        if ($this->permission) {
            $this->authorize("create-$this->permission");
        }

        $string = Str::title(dash_to_space($this->permission));

        $classExport = 'App\Exports\\Template' . $string . 'Export';

        return Excel::download(new $classExport, now()->format('Y-m-d-his') . "-template-{$this->permission}s.xlsx");
    }

    public function importTemplate(Request $request)
    {
        if ($this->permission) {
            $this->authorize("create-$this->permission");
        }
        $string = Str::title(dash_to_space($this->permission));

        $classExport = 'App\Imports\\' . $string . 'Import';

        Excel::import(new $classExport, $request->file("{$this->permission}-import"));

        return;
    }
}
