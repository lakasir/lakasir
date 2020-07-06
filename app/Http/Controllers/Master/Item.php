<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\Master\Item\BulkDelete;
use App\Http\Requests\Master\Item\Delete;
use App\Http\Requests\Master\Item\Index;
use App\Http\Requests\Master\Item\Store;
use App\Http\Requests\Master\Item\Update;
use App\Models\Item as Model;
use App\Repositories\Item as ItemRepository;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class Item extends Controller
{
    /**
     * @var Item
     */
    public ItemRepository $item;

    /**
     * @param ItemRepository $item
     */
    public function __construct()
    {
        $this->item = new ItemRepository();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(Index $request): View
    {
        $this->authorize('browse-item');
        $items = $this->item->paginate($request, ['*'], 'name');

        return view('app.master.items.index', compact('items'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create(): View
    {
        $this->authorize('create-item');

        return view('app.master.items.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param App\Http\Requests\Master\Item\Store $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Store $request): RedirectResponse
    {
        $this->authorize('create-item');
        $this->item->create($request);

        return redirect()->to('/master/item');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Item  $item
     * @return \Illuminate\View\View
     */
    public function show(Model $item): View
    {
        $this->authorize('browse-item');

        return view('app.master.items.show', compact('item'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Item  $item
     * @return \Illuminate\View\View
     */
    public function edit(Model $item)
    {
        $this->authorize('update-item');

        return view('app.master.items.edit', compact('item'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Model  $item
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Update $request, Model $item): RedirectResponse
    {
        $this->authorize('update-item');
        $this->item->update($request, $item);

        return redirect()->to('/master/item');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function destroy(Model $item): RedirectResponse
    {
        $this->authorize('delete-item');
        $item->delete();

        return redirect()->to('/master/item');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  BulkDelete $request
     * @return \Illuminate\Http\Response
     */
    public function bulkDestroy(BulkDelete $request): RedirectResponse
    {
        $this->item->bulkDestroy($request);

        return redirect()->back();
    }
}
