<?php

namespace App\Console;

use App\Console\Commands\MarkExpiredTransactions;
use App\Console\Commands\MarkOverdueInvoices;
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
        MarkExpiredTransactions::class,
        MarkOverdueInvoices::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // Expire pending transactions (every minute)
        $schedule->command('transactions:mark-expired')->everyMinute();

        // Mark overdue invoices (every minute)
        $schedule->command('invoices:mark-overdue')->everyMinute();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        // Load the commands directory
        $this->load(__DIR__ . '/Commands');

        // This loads additional routes for console commands if needed
        require base_path('routes/console.php');
    }
}
