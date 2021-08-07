<?php

namespace App\DataTables;

use App\Html\Item;
use App\Interfaces\WithButton;
use App\Interfaces\WithCheckbox;
use App\Interfaces\WithCreatedHumanDate;
use App\Interfaces\WithOptions;
use App\Models\Group;
use App\Traits\Group\GroupTrait;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;

class GroupDataTable extends BaseDataTable implements
    WithOptions,
    WithButton,
    WithCheckbox,
    WithCreatedHumanDate
{
    use GroupTrait;

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
                ->url(route('group.bulkDestroy'))
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

    public function addOptionsBuilder($group): array
    {
        $permission = $this->getPermission();

        return [
            Item::make(__('app.global.view'))
                ->icon('<i class="fa fa-eye mr-2"></i>')
                ->url(route('group.show', $group))
                ->show($permission['browse']),
            Item::make(__('app.global.edit'))
                ->icon('<i class="fa fa-pen mr-2"></i>')
                ->url(route('group.edit', $group))
                ->show($permission['edit']),
            Item::make(__('app.global.delete'))
                ->icon('<i class="fa fa-trash mr-2"></i>')
                ->method('DELETE')
                ->confirm(__('app.global.confirm.suredelete'))
                ->url(route('group.destroy', $group))
                ->show($permission['delete']),
        ];
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Group $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Group $model)
    {
        return $model->newQuery();
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
                ->title(trans('app.groups.column.name')),
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
}
