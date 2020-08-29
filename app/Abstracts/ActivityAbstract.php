<?php

namespace App\Abstracts;

use App\Jobs\ActivityCreate;
use Illuminate\Http\Request;
use DB;

abstract class ActivityAbstract
{
    /** @var string info */
    protected string $info;

    /** @var boolean auth  */
    protected bool $auth = false;

    protected bool $queue = true;

    /** @var \Illuminate\Http\Request request*/
    protected Request $request;

    protected object $parent;

    protected string $model = \App\Models\Activity::class;

    protected string $property;

    protected const CREATING = 'creating';

    protected const UPDATING = 'updating';

    protected const DELETING = 'deleting';

    protected function create()
    {
        if (method_exists($this->parent, 'logs')) {
            $auth = false;
            if ($this->auth) {
                $auth = auth()->user();
            }
            if ($this->queue) {
                dispatch(new ActivityCreate($this->data(), $this->model, $auth, $this->parent))->delay(now()->addSeconds(1));
            } else {
                dispatch_now(new ActivityCreate($this->data(), $this->model, $auth, $this->parent));
            }
        }
    }

    private function data()
    {
        if (isset($this->property)) {
            $property = $this->property;
        }
        $devices = $this->request->header('user-agent');
        $ip_address = $this->request->server('REMOTE_ADDR');
        $url = $this->request->path();
        $referer = $this->request->header('referer');
        if (app()->environment() == 'testing') {
            $referer = app()->environment();
        }

        return $data = [
            'url' => $url,
            'ip' => $ip_address,
            'devices' => $devices,
            'referer' => $referer,
            'info' => $this->info,
            'request' => json_encode($this->request->all()),
            'property' => $property
        ];
    }

    public function query()
    {
        return $this->model::query();
    }
}
