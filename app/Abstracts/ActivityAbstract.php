<?php

namespace App\Abstracts;

use Illuminate\Http\Request;
use DB;

abstract class ActivityAbstract
{
    /** @var string info */
    protected string $info;

    /** @var boolean auth  */
    protected bool $auth = false;

    /** @var \Illuminate\Http\Request request*/
    protected Request $request;

    protected object $model;

    protected const CREATING = 'creating';

    protected const UPDATING = 'updating';

    protected const DELETING = 'deleting';

    protected function create()
    {
        if (method_exists($this->model, 'logs')) {
            $self = $this;
            return DB::transaction(static function () use ($self) {
                $activity = new \App\Models\Activity();
                $activity->fill($self->data());
                if ($self->auth) {
                    $activity->user()->associate(auth()->user());
                }
                $activity->save();
                if (isset($self->model)) {
                    $self->model->logs()->save($activity);
                }

                return $activity;
            });
        }
    }

    private function data()
    {
        $browser = $this->request->header('user-agent');
        $ip_address = $this->request->server('REMOTE_ADDR');
        $url = $this->request->path();
        $referer = $this->request->header('referer');
        if (app()->environment() == 'testing') {
            $referer = app()->environment();
        }
        return $data = [
            'url' => $url,
            'ip' => $ip_address,
            'browser' => $browser,
            'referer' => $referer,
            'info' => $this->info,
            'request' => json_encode($this->request->all())
        ];
    }
}
