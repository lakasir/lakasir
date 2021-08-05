<?php

namespace App\DataTables;

use App\Html\Item;
use App\Interfaces\WithButton;
use App\Interfaces\WithCheckbox;
use App\Interfaces\WithCreatedHumanDate;
use App\Interfaces\WithOptions;
use App\Models\Customer;
use App\Traits\Customer\CustomerTrait;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;

class CustomerDataTable extends BaseDataTable implements
    WithOptions,
    WithButton,
    WithCheckbox,
    WithCreatedHumanDate

{
    use CustomerTrait;

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Customer $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Customer $model)
    {
        return $model->newQuery()->with('customerType');
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
                ->orderable(false)
                ->width(30)
                ->addClass('text-center'),
            Column::make('name')
                ->title(trans('app.customers.column.name')),
            Column::make('code')
                ->title(trans('app.customers.column.code')),
            Column::make('customer_type.name')
                ->title(trans('app.customers.column.customer_type')),
            Column::make('created_at')
                ->title(trans('app.global.created_at'))
                ->width(120),
            Column::computed('options')
                ->title('')
                ->exportable(false)
                ->printable(false)
                ->orderable(false)
                ->width(60)
                ->addClass('text-center'),
        ];
    }

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
            Button::make('bulkDelete')
                ->text('<i class="fas fa-trash"></i> ' . __('app.global.bulk-delete'))
                ->idTarget('select-row')
                ->url(route('customer_type.bulkDestroy'))
                ->warning(__('app.global.warning.checked_first'))
                ->confirm(__('app.global.confirm.bulk-delete'))
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

    public function addOptionsBuilder($customer): array
    {
        $permission = $this->getPermission();

        return [
            Item::make(__('app.global.view'))
                ->icon('<i class="fa fa-eye mr-2"></i>')
                ->url(route('customer.show', $customer))
                ->show($permission['browse']),
            Item::make(__('app.global.edit'))
                ->icon('<i class="fa fa-pen mr-2"></i>')
                ->url(route('customer.edit', $customer))
                ->show($permission['edit']),
            Item::make(__('app.global.delete'))
                ->icon('<i class="fa fa-trash mr-2"></i>')
                ->method('DELETE')
                ->confirm(__('app.global.confirm.suredelete'))
                ->url(route('customer.destroy', $customer))
                ->show($permission['delete']),
        ];
    }
}
