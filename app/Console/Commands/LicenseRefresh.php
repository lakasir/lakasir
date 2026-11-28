<?php

namespace App\Console\Commands;

use App\Models\License;
use App\Services\License\CloudClient;
use Illuminate\Console\Command;

class LicenseRefresh extends Command
{
    protected $signature = 'license:refresh {--key= : Activate with this key first}';

    protected $description = 'Fetch a fresh licence token from lakasir-cloud';

    public function handle(CloudClient $client): int
    {
        if ($key = $this->option('key')) {
            $result = $client->activate($key);
        } else {
            $result = $client->refresh();
        }

        if (! $result['ok']) {
            License::current()->update(['last_refresh_error' => $result['message']]);

            $this->error($result['message']);

            return self::FAILURE;
        }

        $this->info($result['message']);

        return self::SUCCESS;
    }
}
