<?php

namespace App\Helpers;

use App\Abstracts\ActivityAbstract;
use App\Models\Activity as ActivityModel;
use Illuminate\Http\Request;

class Activity extends ActivityAbstract
{
    public function __construct()
    {
        $this->request = request();
    }

    public function parent(object $parent)
    {
        $this->parent = $parent;
        return $this;
    }

    public function auth()
    {
        $this->auth = true;
        return $this;
    }

    public function info(string $info)
    {
        $this->inf0 = $info;

        return $this;
    }

    public function creating()
    {
        $this->info = self::CREATING;
        $this->property = json_encode(['created' => $this->parent->toArray()]);

        return $this->create();
    }

    public function updating()
    {
        $this->info = self::UPDATING;
        $this->property = json_encode(['created' => $this->parent->toArray()]);

        return $this->create();
    }

    public function deleting()
    {
        $this->info = self::DELETING;
        $this->property = json_encode(['deleted' => $this->parent->toArray()]);

        return $this->create();
    }

    public function sync()
    {
        $this->queue = false;

        return $this;
    }
}
