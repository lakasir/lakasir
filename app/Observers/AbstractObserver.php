<?php

namespace App\Observers;

use Illuminate\Contracts\Validation\DataAwareRule;

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
                $this->setData(json_decode(request()->all()['components'][0]['snapshot'], true)['data']['data'][0] ?? []);
            } else {
                $this->setData(request()->all());
            }
        }
    }
}
