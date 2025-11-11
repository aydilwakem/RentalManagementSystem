<?php

namespace App\Console\Commands;

use App\Mail\EventHoursExpiredMail;
use Illuminate\Console\Command;
use App\Models\Transaction;
use Carbon\Carbon;
use App\Models\Setting;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

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
        /**
         * ROOMS
         */
        // Process room reservations (reservation_type_id = 2)
        $roomExpired = Transaction::whereIn('transaction_status', ['pending'])
            ->where('reservation_type_id', 2) // Room reservations
            ->where('created_at', '<=', Carbon::now()->subHours($settings->room_payment_proof_expiration_hours))
            ->update(['transaction_status' => 'expired']);
        $expiredCount += $roomExpired;

        /**
         * EVENTS
         */
        // Process event reservations (reservation_type_id = 3)
        $eventTransactions = Transaction::whereIn('transaction_status', ['pending'])
            ->where('reservation_type_id', 3) // Event reservations
            ->where('created_at', '<=', Carbon::now()->subHours($settings->event_payment_proof_expiration_hours))
            ->get(); 

        $eventExpiredCount = $eventTransactions->count();
        Log::info("Found {$eventExpiredCount} event transactions to mark as expired.");

            foreach($eventTransactions as $eventTransaction){
                $eventTransaction->update(['transaction_status' => 'expired']);
                Log::info("Transaction ID {$eventTransaction->id} marked as expired (created at: {$eventTransaction->created_at}).");


                //Try to send email
                try {
            if ($eventTransaction->transactionUser && $eventTransaction->transactionUser->email) {
                Mail::to($eventTransaction->transactionUser->email)
                    ->send(new EventHoursExpiredMail($eventTransaction, $settings));

                Log::info("Successfully sent EventHoursExpiredMail to {$eventTransaction->transactionUser->email} for transaction ID: {$eventTransaction->id}");
            } else {
                Log::warning("No email found for transaction ID: {$eventTransaction->id}. Skipping mail send.");
            }
        } catch (\Throwable $e) {
            Log::error("Failed to send EventHoursExpiredMail for transaction ID {$eventTransaction->id}. Error: " . $e->getMessage());
        }

        $expiredCount++;
    }


        /**
         * DAYTOUR
         */
        // Process day tour reservations (reservation_type_id = 4)
        $dayTourExpired = Transaction::whereIn('transaction_status', ['pending'])
            ->where('reservation_type_id', 4) // Day tour reservations
            ->where('created_at', '<=', Carbon::now()->subHours($settings->day_tour_payment_proof_expiration_hours))
            ->update(['transaction_status' => 'expired']);
        $expiredCount += $dayTourExpired;

        $this->info("Expired transactions updated: {$expiredCount} (Rooms: {$roomExpired}, Events: {$eventExpiredCount}, Day Tours: {$dayTourExpired})");
    }
    
}
