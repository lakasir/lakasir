<?php

namespace App\Services;

use App\Abstracts\Repository as RepositoryAbstract;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Group extends RepositoryAbstract
{
    protected string $model = 'App\Models\Group';

    public function create(Request $request)
    {
        $self = $this;
        return DB::transaction(static function () use ($self, $request) {
            $group = $self->model::create($request->all());
            $customer = ( new Customer() )->findByKeyArray($request->customer_id);
            $group->customers()->attach($customer);

            return $group;
        });
    }

    public function update(Request $request, $group)
    {
        $self = $this;
        return DB::transaction(static function () use ($self, $request, $group) {
            $group->fill($request->all());
            $group->save();
            $customer = ( new Customer() )->findByKeyArray($request->customer_id);
            $group->customers()->sync($customer);

            return $group;
        });
    }

    public function datatable(Request $request)
    {
        $items = $this->model::withCount([ 'customers' ])->toBase()->latest()->get();

        return $this->getobjectmodel()->table($items);
    }
}
