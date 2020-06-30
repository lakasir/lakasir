<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Artisan;

class UpdateEnv implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var Request
     */
    private array $data;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $path = base_path('.env');
        if (file_exists($path)) {
            foreach ($this->data as $key => $val) {
                file_put_contents($path, str_replace(
                    $key.'='.env($key),
                    $key.'='.$val,
                    file_get_contents($path)
                ));
            }
       }

        Artisan::call('migrate:refresh');
        Artisan::call('migrate');
    }
}
