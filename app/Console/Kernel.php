<?php

namespace App\Console;

use App\Console\Commands\DeleteTempFile;
use App\Console\Commands\FCM;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule)
    {
        $schedule->command(DeleteTempFile::class)->daily();
        $schedule->command(FCM::class)->daily();
    }

    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
