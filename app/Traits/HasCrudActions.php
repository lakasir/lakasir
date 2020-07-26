<?php

namespace App\Traits;

use App\Exceptions\ServiceActionsException;
use App\Http\Requests\Master\Unit\Index;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

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
     * @param Yajra\DataTables\Html\Builder $builder
     * @return mix
     */
    public function index()
    {
        $request = resolve($this->indexRequest);
        $this->authorize("browse-$this->permission");
        if ($request->ajax()) {
            return $this->repository->datatable($request);
        }

        return view("{$this->viewPath}.index");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create(): View
    {
        $this->authorize("create-$this->permission");

        return view("{$this->viewPath}.create");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(): RedirectResponse
    {
        $request = resolve($this->storeRequest);

        $this->authorize("create-$this->permission");

        if (isset($this->storeService)) {
            if (count($this->storeService) > 2) {
                throw new ServiceActionsException('Store Service property is cant to more 2 index');
            }
            if (!is_array($this->storeService)) {
                throw new ServiceActionsException('Store Service property must be array');
            }
            ( new $this->storeService[0] )->{$this->storeService[1]}($request);
        } else {
            $this->repository->create($request);
        }

        return redirect()->to($this->redirect);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $model
     * @return \Illuminate\View\View
     */
    public function show(int $model): View
    {
        $data = $this->repository->find($model);

        $this->authorize("browse-{$this->permission}");

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
        $data = $this->repository->find($model);

        $request = resolve($this->updateRequest);

        $this->authorize("update-{$this->permission}");

        $this->repository->update($request, $data);

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
        $this->authorize("delete-{$this->permission}");

        $this->repository->find($model)->delete();

        return redirect()->to($this->redirect);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function bulkDestroy(): RedirectResponse
    {
        $request = resolve($this->bulkDestroyRequest);

        $this->repository->bulkDestroy($request);

        return redirect()->back();
    }
}
