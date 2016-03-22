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
        // Commands\Inspire::class,
          Commands\StartCrawler::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();

        $schedule->command('crawler:start --download=1 --key=0')
                 ->daily();
        $schedule->command('crawler:start --download=1 --key=1')
            ->daily();
        $schedule->command('crawler:start --download=1 --key=2')
            ->daily();
        $schedule->command('crawler:start --download=1 --key=3')
            ->daily();
        $schedule->command('crawler:start --download=1 --key=4')
            ->daily();
        $schedule->command('crawler:start --download=1 --key=5')
            ->daily();
        $schedule->command('crawler:start --download=1 --key=6')
            ->daily();

        $schedule->command('crawler:start --download=1 --key=7')
            ->daily();
    }
}
