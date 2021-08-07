<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\Group as GroupModel;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Group
{
    /**
     * @param Request $request
     * @return GroupModel
     * @throws Exception
     */
    public function create(Request $request)
    {
        try {
            DB::beginTransaction();
            /** @var GroupModel $group */
            $group = GroupModel::create($request->all());
            $group->customers()->saveMany(Customer::find($request->customers));

            DB::commit();

            return $group;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * @param Request $request
     * @param GroupModel $group
     * @return GroupModel|void
     * @throws Exception
     */
    public function update(Request $request, GroupModel $group)
    {
        try {
            DB::beginTransaction();
            /** @var GroupModel $group */
            $group->update($request->all());
            $customers = Customer::find($request->customers);
            $group->customers()->detach();
            $group->customers()->attach($customers);
            DB::commit();

            return $group;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * @param Request $request
     * @param string $column
     * @return void
     * @throws InvalidArgumentException
     * @throws HttpException
     * @throws NotFoundHttpException
     */
    public function bulkDestroy(Request $request, string $column = 'id'): void
    {
        $group_query = GroupModel::query();
        if ($group_query->find($request->ids)->count() == 0) {
            abort(404);
        }
        DB::transaction(static function () use ($request, $column, $group_query) {
            collect($request->ids)
                ->chunk(1000)
                ->each(static function ($bulkChunk) use ($column, $group_query) {
                    $group_query->whereIn($column, $bulkChunk)->delete();
                });
        });
    }
}
