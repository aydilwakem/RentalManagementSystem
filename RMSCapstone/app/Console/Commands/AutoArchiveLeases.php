<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Transaction;
use Carbon\Carbon;

class AutoArchiveLeases extends Command
{
    protected $signature = 'leases:auto-archive';
    protected $description = 'Automatically archive leases older than 5 years';

    public function handle()
    {
        $archivedCount = Transaction::where('reservation_type_id', 1) // House leases
            ->shouldBeArchived()
            ->update(['transaction_status' => 'archived']);

        $this->info("Archived {$archivedCount} leases older than 5 years.");
        
        return Command::SUCCESS;
    }
}