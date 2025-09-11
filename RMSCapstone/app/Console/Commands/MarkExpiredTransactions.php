<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Transaction;
use Carbon\Carbon;
use App\Models\Setting;

class MarkExpiredTransactions extends Command
{
    protected $signature = 'transactions:mark-expired';

    protected $description = 'Mark transactions as expired if older than the configured expiration time and still pending upload';

    public function handle()
    {
        // Fetch the configured expiration time 
        $expirationHours = Setting::first()->payment_proof_expiration_hours;

        // Mark transactions as expired if their 'created_at' is older than the expiration timeframe
        $expiredTransactions = Transaction::whereIn('transaction_status', ['pending'])
            ->where('created_at', '<=', Carbon::now()->subHours($expirationHours))
            ->update(['transaction_status' => 'expired']);

        $this->info("Expired transactions updated: $expiredTransactions");
    }
}
