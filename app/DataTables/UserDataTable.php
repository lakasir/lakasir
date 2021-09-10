<?php

namespace App\DataTables;

use App\Html\Item;
use App\Interfaces\WithButton;
use App\Interfaces\WithCheckbox;
use App\Interfaces\WithCreatedHumanDate;
use App\Interfaces\WithCustomColumn;
use App\Interfaces\WithOptions;
use App\Models\User;
use App\Traits\User\UserTrait;
use Illuminate\Contracts\Container\BindingResolutionException;
use Yajra\DataTables\DataTableAbstract;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;

/** @package App\DataTables */
class UserDataTable extends BaseDataTable implements
    WithOptions,
    WithButton,
    WithCheckbox,
    WithCreatedHumanDate,
    WithCustomColumn
{
    use UserTrait;

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\User $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(User $model)
    {
        return $model->newQuery()->with('roles');
    }

    /**
     * @param DataTableAbstract $datatbale
     * @return DataTableAbstract
     */
    public function customColumn($datatbale)
    {
        return $datatbale->addColumn('roles_name', function (User $row)
        {
            return optional($row->roles()->first())->name ?? '-';
        });
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
            Column::make('username')
                ->title(trans('app.user.column.username'))
                ->width(120),
            Column::make('email')
                ->title(trans('app.user.column.email'))
                ->width(120),
            Column::make('roles_name')
                ->title(trans('app.user.column.role'))
                ->width(120),
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
                ->url(route('user.bulkDestroy'))
                ->warning(__('app.global.warning.checked_first')),
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

    /**
     * @param User $user
     * @return array
     * @throws BindingResolutionException
     */
    public function addOptionsBuilder($user): array
    {
        $permission = $this->getPermission();

        return [
            Item::make(__('app.global.view'))
                ->icon('<i class="fa fa-eye mr-2"></i>')
                ->url(route('user.show', $user))
                ->show($permission['browse']),
            Item::make(__('app.global.edit'))
                ->icon('<i class="fa fa-pen mr-2"></i>')
                ->url(route('user.edit', $user))
                ->show($permission['edit']),
            Item::make(__('app.global.delete'))
                ->icon('<i class="fa fa-trash mr-2"></i>')
                ->method('DELETE')
                ->confirm(__('app.global.confirm.suredelete'))
                ->url(route('user.destroy', $user))
                ->show($permission['delete']),
            Item::make(__('app.user.custom_action.assign_role'))
                ->icon('<i class="fa fa-pen mr-2"></i>')
                ->addClass('action-assign-role')
                ->setData([
                    'id' => $user->getKey(),
                    'key' => bcrypt($user->getKey()),
                    'routes' => route('user.update', $user->getKey()),
                    'role' => $user->roles()->first()
                ])
                ->show($permission['edit']),
        ];
    }
}
