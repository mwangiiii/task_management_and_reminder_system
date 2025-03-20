<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Log;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // Run the command every minute to check for task alerts
        Log::info("Here");
        //$schedule->command('tasks:check-alerts')->everyMinute();
        $schedule->command('tasks:send-alerts')
         ->everyMinute()
         ->withoutOverlapping()
         ->before(function () {
             Log::info('SendTaskAlerts is about to run.');
         })
         ->after(function () {
             Log::info('SendTaskAlerts has finished.');
         });

    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}