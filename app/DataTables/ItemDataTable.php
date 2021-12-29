<?php

namespace App\DataTables;

use App\Html\Item;
use App\Interfaces\WithButton;
use App\Interfaces\WithCheckbox;
use App\Interfaces\WithCreatedHumanDate;
use App\Interfaces\WithCustomColumn;
use App\Interfaces\WithOptions;
use App\Models\Item as ModelsItem;
use App\Traits\Item\ItemTrait;
use Yajra\DataTables\DataTableAbstract;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;

/** @package App\DataTables */
class ItemDataTable extends BaseDataTable implements
    WithOptions,
    WithButton,
    WithCheckbox,
    WithCreatedHumanDate,
    WithCustomColumn
{
    use ItemTrait;

    /**
     * @return array[]
     * @throws BindingResolutionException
     */
    public function getButton(): array
    {
        $create = $this->getPermission()['create'];

        return [
            Button::make('create')
                ->text('<i class="fa fa-plus"></i> ' . __('app.global.create'))
                ->authorized($create),
            Button::make('reload')
                ->text('<i class="fas fa-sync"></i> ' . __('app.global.reload')),
            /* Button::make('bulkDelete') */
            /*     ->text('<i class="fas fa-trash"></i> ' . __('app.global.bulk-delete')) */
            /*     ->idTarget('select-row') */
            /*     ->url(route('group.bulkDestroy')) */
            /*     ->warning(__('app.global.warning.checked_first')) */
            /*     ->confirm(__('app.global.confirm.bulk-delete')) */
        ];
    }

    /**
     * @return array
     * @throws BindingResolutionException
     */
    private function getPermission()
    {
        $auth = optional(auth()->user() ?? '');
        $browse = $auth->can("browse-{$this->prefixPermission()}");
        $edit = $auth->can("update-{$this->prefixPermission()}");
        $delete = $auth->can("delete-{$this->prefixPermission()}");
        $create = $auth->can("create-{$this->prefixPermission()}");

        return [
            'browse' => $browse,
            'edit' => $edit,
            'delete' => $delete,
            'create' => $create
        ];
    }

    public function addOptionsBuilder($item): array
    {
        $permission = $this->getPermission();

        return [
            Item::make(__('app.global.view'))
                ->icon('<i class="fa fa-eye mr-2"></i>')
                ->url(route('item.show', $item))
                ->show($permission['browse']),
            Item::make(__('app.global.edit'))
                ->icon('<i class="fa fa-pen mr-2"></i>')
                ->url(route('item.edit', $item))
                ->show($permission['edit']),
            /* Item::make(__('app.global.delete')) */
            /*     ->icon('<i class="fa fa-trash mr-2"></i>') */
            /*     ->method('DELETE') */
            /*     ->confirm(__('app.global.confirm.suredelete')) */
            /*     ->url(route('item.destroy', $item)) */
            /*     ->show($permission['delete']), */
        ];
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\ModelsItem $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(ModelsItem $model)
    {
        return $model->load('prices', 'log_stocks')->newQuery();
    }

    /**
     * Get columns.
     *
     * @return array
     */
    public function getColumns(): array
    {
        return [
            Column::computed('checkbox')
                ->title('#')
                  ->exportable(false)
                  ->printable(false)
                  ->width(60)
                  ->addClass('text-center'),
            Column::make('name')
                ->title(trans('app.items.column.name')),
            Column::make('initial_price')
                ->title(trans('app.items.column.price.initial_price')),
            Column::make('selling_price')
                ->title(trans('app.items.column.price.selling_price')),
            Column::make('stock')
                ->title(trans('app.items.column.stock.amount')),
            Column::make('created_at')
                ->title(trans('app.global.created_at'))
                ->width(120),
            Column::computed('options')
                ->exportable(false)
                ->printable(false)
                ->width(60)
                ->addClass('text-center'),
        ];
    }

    /**
     * @param DataTableAbstract $datatbale
     * @return DataTableAbstract
     */
    public function customColumn($datatbale)
    {
        return $datatbale->addColumn('initial_price', function (ModelsItem $item)
        {
            return price_format($item->getLastPriceAttribute()->initial_price);
        })->addColumn('selling_price', function (ModelsItem $item)
        {
            return price_format($item->getLastPriceAttribute()->selling_price);
        })->addColumn('stock', function (ModelsItem $item)
        {
            return $item->getLastStockAttribute()->amount;
        });
    }
}
