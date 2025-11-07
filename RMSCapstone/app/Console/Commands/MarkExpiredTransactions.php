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
        $settings = Setting::first();
        if (!$settings) {
            $this->error('No settings found.');
            return;
        }

        $expiredCount = 0;

        // Process room reservations (reservation_type_id = 2)
        $roomExpired = Transaction::whereIn('transaction_status', ['pending'])
            ->where('reservation_type_id', 2) // Room reservations
            ->where('created_at', '<=', Carbon::now()->subHours($settings->room_payment_proof_expiration_hours))
            ->update(['transaction_status' => 'expired']);
        $expiredCount += $roomExpired;

        // Process event reservations (reservation_type_id = 3)
        $eventExpired = Transaction::whereIn('transaction_status', ['pending'])
            ->where('reservation_type_id', 3) // Event reservations
            ->where('created_at', '<=', Carbon::now()->subHours($settings->event_payment_proof_expiration_hours))
            ->update(['transaction_status' => 'expired']);
        $expiredCount += $eventExpired;

        // Process day tour reservations (reservation_type_id = 4)
        $dayTourExpired = Transaction::whereIn('transaction_status', ['pending'])
            ->where('reservation_type_id', 4) // Day tour reservations
            ->where('created_at', '<=', Carbon::now()->subHours($settings->day_tour_payment_proof_expiration_hours))
            ->update(['transaction_status' => 'expired']);
        $expiredCount += $dayTourExpired;

        $this->info("Expired transactions updated: {$expiredCount} (Rooms: {$roomExpired}, Events: {$eventExpired}, Day Tours: {$dayTourExpired})");
    }
    
}
