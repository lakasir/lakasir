<?php

namespace App\Traits;

use App\Models\Activity;

/**
 * Trait HasLog
 * @author sheenazien
 */
trait HasLog
{
    public function logs()
    {
        return $this->morphMany(Activity::class, 'modelable');
    }
}
