<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\Master\Unit\BulkDelete;
use App\Http\Requests\Master\Unit\Delete;
use App\Http\Requests\Master\Unit\Index;
use App\Http\Requests\Master\Unit\Store;
use App\Http\Requests\Master\Unit\Update;
use App\Models\Unit as Model;
use App\Repositories\Unit as UnitRepository;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class Unit extends Controller
{
    /**
     * @var Unit
     */
    public UnitRepository $unit;

    /**
     * @param UnitRepository $unit
     */
    public function __construct()
    {
        $this->unit = new UnitRepository();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(Index $request): View
    {
        $this->authorize('browse-unit');
        $units = $this->unit->paginate($request, ['name'], 'name');

        return view('app.master.units.index', compact('units'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create(): View
    {
        $this->authorize('create-unit');

        return view('app.master.units.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param App\Http\Requests\Master\Unit\Store $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Store $request): RedirectResponse
    {
        $this->authorize('create-unit');
        $this->unit->create($request);

        return redirect()->to('/master/unit');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Unit  $unit
     * @return \Illuminate\View\View
     */
    public function show(Model $unit): View
    {
        $this->authorize('browse-unit');

        return view('app.master.units.show', compact('unit'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Unit  $unit
     * @return \Illuminate\View\View
     */
    public function edit(Model $unit)
    {
        $this->authorize('update-unit');

        return view('app.master.units.edit', compact('unit'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Model  $unit
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Update $request, Model $unit): RedirectResponse
    {
        $this->authorize('update-unit');
        $this->unit->update($request, $unit);

        return redirect()->to('/master/unit');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Unit  $unit
     * @return \Illuminate\Http\Response
     */
    public function destroy(Model $unit): RedirectResponse
    {
        $this->authorize('delete-unit');
        $unit->delete();

        return redirect()->to('/master/unit');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  BulkDelete $request
     * @return \Illuminate\Http\Response
     */
    public function bulkDestroy(BulkDelete $request): RedirectResponse
    {
        $this->unit->bulkDestroy($request);

        return redirect()->back();
    }
}
