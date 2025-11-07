<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Transaction;
use Carbon\Carbon;

class AutoArchiveDayTours extends Command
{
    protected $signature = 'daytours:auto-archive';
    protected $description = 'Automatically archive day tours older than 5 years';

    public function handle()
    {
        $archivedCount = Transaction::where('reservation_type_id', 4) // Day Tour reservation type
            ->shouldBeArchived()
            ->update(['transaction_status' => 'archived']);

        $this->info("Archived {$archivedCount} day tours older than 5 years.");
        
        return Command::SUCCESS;
    }
}