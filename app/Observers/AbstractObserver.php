<?php

namespace App\Observers;

use Illuminate\Contracts\Validation\DataAwareRule;

/**
 * Class AbstractObserver
 * @author sheenazien8
 */
abstract class AbstractObserver
{
    /**
     * @param
     */
    public function __construct()
    {
        if (method_exists($this, 'setData') && $this instanceof DataAwareRule) {
            $this->setData(request()->all());
        }
    }

}
