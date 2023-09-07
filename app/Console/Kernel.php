<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // v2board
        $schedule->command('v2board:statistics')->dailyAt('00:10');
        // check
        $schedule->command('check:order')->everyMinute();
        $schedule->command('check:commission')->everyMinute();
        $schedule->command('check:server')->everyMinute();
        $schedule->command('check:email')->everyThirtyMinutes();

        if (config('app.locale') === 'zh-CN') {
            $schedule->command('check:server_gfw')->hourly();
        }
        // reset
        $schedule->command('reset:traffic')->dailyAt('00:01');
        // send
        $schedule->command('send:remindMail')->dailyAt('11:30');
        // horizon metrics
        $schedule->command('horizon:snapshot')->everyFiveMinutes();
        // backup db
        $schedule->command('backup:clean --disable-notifications')->daily()->at('04:10');
        $schedule->command('backup:run --only-db --disable-notifications')->daily()->at('04:30');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}