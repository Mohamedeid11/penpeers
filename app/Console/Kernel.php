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
        Commands\RemindAuthorsOfChapterDeadline::class,
        Commands\EmailRenewPlan::class,
        Commands\EmailEndTrial::class,
        Commands\RemindAuthorsByTheRequestsPending::class
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
        $schedule->command('deadline:reminder')->daily();
        $schedule->command('requests-pending:reminder')->daily();
        $schedule->command('email:RenewPlanUser')->daily();
        $schedule->command('email:EndTrial')->daily();
        $schedule->command('delete:book')->daily();
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
