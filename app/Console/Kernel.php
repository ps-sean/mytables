<?php

namespace App\Console;

use App\Console\Commands\BookingPreAuths;
use App\Jobs\ReviewReminder;
use App\Jobs\UnreadMessageEmailer;
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
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();

        $schedule->job(new UnreadMessageEmailer)->everyFiveMinutes();

        $schedule->job(new ReviewReminder)->everyFiveMinutes();

        $schedule->job(new BookingPreAuths)->everyFiveMinutes();

        $schedule->command("restaurant:invoice")->dailyAt("06:00");

        $schedule->command('horizon:snapshot')->everyFiveMinutes();

        $schedule->command('telescope:prune')->daily();
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
