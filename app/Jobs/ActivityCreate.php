<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class ActivityCreate implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $data;

    private $query;

    private $model;

    private $auth;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data, $query, $auth, $model = null)
    {
        $this->data = $data;
        $this->query = $query;
        $this->model = $model;
        $this->auth = $auth;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $self = $this;
        return DB::transaction(static function () use ($self) {
            $activity = new $self->query;
            $activity->fill($self->data);
            if ($self->auth) {
                $activity->user()->associate($self->auth);
            }
            $activity->save();
            if (isset($self->model)) {
                $self->model->logs()->save($activity);
            }

            return $activity;
        });
    }
}
