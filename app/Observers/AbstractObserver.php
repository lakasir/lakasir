<?php

namespace App\Observers;

use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Support\Arr;

/**
 * Class AbstractObserver
 *
 * @author sheenazien8
 */
abstract class AbstractObserver
{
    public function __construct()
    {
        if (method_exists($this, 'setData') && $this instanceof DataAwareRule) {
            if (isset(request()->all()['components'])) {
                $data = Arr::undot(request()->all()['components'][0]['updates'])['data'] ?? [];
                $this->setData($data);
            } else {
                $this->setData(request()->all());
            }
        }
    }
}
