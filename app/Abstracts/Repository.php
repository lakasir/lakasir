<?php

namespace App\Abstracts;

use App\Interfaces\Repository as RepositoryInterface;
use Illuminate\Support\Facades\DB;

abstract class Repository implements RepositoryInterface
{
    /** @var string model */
    protected string $model;

    public function find($id)
    {
        return $this->model::find($id);
    }

    public function delete($id)
    {
        return $this->model::find($id)->delete();
    }

    public function paginate($request, $columns = ['*'], $search)
    {
        $self = $this;
        return $this->model::select($columns)
                    ->when(isset($this->parent) && ! is_null($this->parent), function ($query)
                        use ($self) {
                            return $query->where($self->column, $self->parent->id);
                        })
                    ->when(! is_null($request->s), function ($query)
                        use ($request, $search) {
                            return $query->where($search, 'LIKE', $request->s.'%%');
                        })
                        ->orderBy('id', 'desc')
                        ->paginate($request->per_page);
    }

    public function all($columns)
    {
        return $this->model::select($columns)->get();
    }

    public function get($request, $columns, $search)
    {
        return $this->model::select($columns)->when(! is_null($request->s), function ($query)
            use ($request, $search) {
                return $query->where($search, 'LIKE', $request->s.'%%');
            })
            ->orderBy('id', 'desc')
            ->get();
    }

    public function create($request)
    {
        $model = new $this->model;
        $model->fill($request->all());
        if (isset($this->parent)) {
            $model->{strtolower(class_basename($this->parent))}()->associate($this->parent);
        }
        $model->save();

        return $model;
    }

    public function update($request, $model)
    {
        $model->fill($request->all());
        if (isset($this->parent)) {
            $model->{strtolower(class_basename($this->parent))}()->associate($this->parent);
        }
        $model->save();

        return $model;
    }

    public function bulkDestroy($request, $column = 'id')
    {
        $self = $this;
        DB::transaction(static function () use ($request, $self, $column) {
            collect($request->ids)
                ->chunk(1000)
                ->each(static function ($bulkChunk) use ($self, $column) {
                    $self->model::whereIn($column, $bulkChunk)->delete();
                });
        });
    }
}
