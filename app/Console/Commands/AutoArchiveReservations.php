<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Transaction;
use Carbon\Carbon;

class AutoArchiveReservations extends Command
{
    protected $signature = 'reservations:auto-archive';
    protected $description = 'Automatically archive reservations older than 5 years';

    public function handle()
    {
        $archivedCount = Transaction::shouldBeArchived()
            ->update(['transaction_status' => 'archived']);

        $this->info("Archived {$archivedCount} reservations older than 5 years.");
        
        return Command::SUCCESS;
    }
}