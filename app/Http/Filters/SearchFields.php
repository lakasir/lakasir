<?php

namespace App\Http\Filters;

use Spatie\QueryBuilder\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class SearchFields implements Filter
{
    public function __invoke(Builder $query, $value, string $fields): Builder
    {
        if (!is_array($fields)) $fields = explode(',', $fields);

        $query->where(function($query) use ($fields, $value) {
            foreach ($fields as $field) {
                // Process nested relationship searches differently
                if (stripos($field, '.')) {
                    $els = explode('.', $field);
                    $relation = $els[0];
                    $col = $els[1];

                    $query->orWhereHas($relation, function($query) use ($value, $col) {
                        $query->where($col, 'LIKE', "%$value%");
                    });
                } else {
                    $query->orWhere($field, 'LIKE', "%$value%");
                }
            }
        });

        return $query;
    }
}
