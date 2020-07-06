<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\Master\Supplier\BulkDelete;
use App\Http\Requests\Master\Supplier\Index;
use App\Http\Requests\Master\Supplier\Store;
use App\Http\Requests\Master\Supplier\Update;
use App\Models\Supplier as Model;
use App\Repositories\Supplier as SupplierRepository;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class Supplier extends Controller
{
    /**
     * @var Supplier
     */
    public SupplierRepository $supplier;

    /**
     * @param SupplierRepository $supplier
     */
    public function __construct()
    {
        $this->supplier = new SupplierRepository();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(Index $request): View
    {
        $this->authorize('browse-supplier');
        $suppliers = $this->supplier->paginate($request, ['name'], 'name');

        return view('app.master.suppliers.index', compact('suppliers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create(): View
    {
        $this->authorize('create-supplier');

        return view('app.master.suppliers.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param App\Http\Requests\Master\Supplier\Store $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Store $request): RedirectResponse
    {
        $this->authorize('create-supplier');
        $this->supplier->create($request);

        return redirect()->to('/master/supplier');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Supplier  $supplier
     * @return \Illuminate\View\View
     */
    public function show(Model $supplier): View
    {
        $this->authorize('browse-supplier');

        return view('app.master.suppliers.show', compact('supplier'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Supplier  $supplier
     * @return \Illuminate\View\View
     */
    public function edit(Model $supplier)
    {
        $this->authorize('update-supplier');

        return view('app.master.suppliers.edit', compact('supplier'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Model  $supplier
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Update $request, Model $supplier): RedirectResponse
    {
        $this->authorize('update-supplier');
        $this->supplier->update($request, $supplier);

        return redirect()->to('/master/supplier');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function destroy(Model $supplier): RedirectResponse
    {
        $this->authorize('delete-supplier');
        $supplier->delete();

        return redirect()->to('/master/supplier');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  BulkDelete $request
     * @return \Illuminate\Http\Response
     */
    public function bulkDestroy(BulkDelete $request): RedirectResponse
    {
        $this->supplier->bulkDestroy($request);

        return redirect()->back();
    }
}
