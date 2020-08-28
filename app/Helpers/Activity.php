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

    public function model(object $model)
    {
        $this->model = $model;
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
        return $this->create();
    }

    public function updating()
    {
        $this->info = self::UPDATING;
        return $this->create();
    }

    public function deleting()
    {
        $this->info = self::DELETING;
        return $this->create();
    }
}
