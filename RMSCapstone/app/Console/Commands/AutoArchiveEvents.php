<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Transaction;
use Carbon\Carbon;

class AutoArchiveEvents extends Command
{
    protected $signature = 'events:auto-archive';
    protected $description = 'Automatically archive events older than 5 years';

    public function handle()
    {
        $archivedCount = Transaction::where('reservation_type_id', 3) // Event reservations
            ->shouldBeArchived()
            ->update(['transaction_status' => 'archived']);

        $this->info("Archived {$archivedCount} events older than 5 years.");
        
        return Command::SUCCESS;
    }
}