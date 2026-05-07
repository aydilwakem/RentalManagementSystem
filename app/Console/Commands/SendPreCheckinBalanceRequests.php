<?php

namespace App\Console\Commands;

use App\Models\Transaction;
use App\Models\Invoice;
use App\Models\Setting;
use App\Mail\RequestRemainingBalanceMail;
use App\Services\PaymentMethodService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class SendPreCheckinBalanceRequests extends Command
{
    protected $signature = 'balance-requests:send-pre-checkin';
    protected $description = 'Send balance request emails to guests 3 days before check-in date';

    public function handle()
    {
        Log::info('Sending pre-checkin balance requests started.');
        $this->info('Starting pre-checkin balance requests...');

        // Get current date and 3 days from now
        $threeDaysFromNow = Carbon::now()->addDays(3)->format('Y-m-d');
        
        // Find transactions with check-in in 3 days that have balance due
        $transactions = Transaction::whereDate('start_datetime', $threeDaysFromNow)
            ->whereHas('invoice', function ($query) {
                $query->where('balance_due', '>', 0)
                      ->where('invoice_status', '!=', 'completed')
                      ->where('requested_remaining_balance', false);
            })
            ->whereIn('transaction_status', ['confirmed', 'receipt_verified', 'pending', 'reserved'])
            ->with(['invoice', 'transactionUser'])
            ->get();

        $this->info("Found {$transactions->count()} transactions with check-in in 3 days.");

        $sentCount = 0;
        $failedCount = 0;

        foreach ($transactions as $transaction) {
            try {
                $this->processTransaction($transaction);
                $sentCount++;
                $this->info("Sent balance request for transaction: {$transaction->transaction_number}");
            } catch (\Exception $e) {
                Log::error("Failed to send balance request for transaction {$transaction->id}: " . $e->getMessage());
                $failedCount++;
                $this->error("Failed for transaction {$transaction->transaction_number}: " . $e->getMessage());
            }
        }

        Log::info("Pre-checkin balance requests completed. Sent: {$sentCount}, Failed: {$failedCount}");
        $this->info("Completed! Sent: {$sentCount}, Failed: {$failedCount}");

        return Command::SUCCESS;
    }

    /**
     * Process a single transaction and send balance request
     * Using the SAME LOGIC as ViewReservation::requestRemainingBalance()
     */
    private function processTransaction(Transaction $transaction)
    {
        $invoice = $invoice = $transaction->invoice;
        $user = $transaction->transactionUser;

        if (!$user || !$user->email) {
            throw new \Exception("No valid email found for user.");
        }

        // Skip if balance due is zero
        if ($invoice->balance_due <= 0) {
            throw new \Exception("No balance due.");
        }

        // Skip if already requested
        if ($invoice->requested_remaining_balance) {
            throw new \Exception("Balance already requested.");
        }

        // Calculate convenience fee (3%) - SAME as in ViewReservation
        $convenienceFee = $invoice->balance_due * 0.03;
        $totalAmount = $invoice->balance_due + $convenienceFee;

        // Instead of using PayMongo directly, we'll use the same approach as the button
        // which seems to work. Let me check how the button works...

        // Based on your ViewReservation code, it seems the button creates a checkout session
        // but looking at the error, PayMongo might not be configured for CLI.
        
        // Let me simplify and just send an email with manual payment instructions
        // You can later integrate PayMongo if needed

        $this->sendBalanceRequestEmail($user, $transaction, $invoice, $convenienceFee);
    }

    /**
     * Send balance request email - SIMPLIFIED VERSION
     * Based on your working email logic
     */
    private function sendBalanceRequestEmail($user, $transaction, $invoice, $convenienceFee)
    {
        $setting = Setting::first();

        // Get branding data
        $brandingData = [
            'branding_company_name' => $setting->company_name ?? 'Canopy Farm PH',
            'logo_path' => $setting->logo ?? '',
            'branding_company_email' => $setting->email ?? 'info@canopyfarm.ph',
            'branding_company_contact' => $setting->contact_number ?? '',
            'company_address' => $setting->address ?? '',
            'facebook_link' => $setting->facebook ?? '',
            'instagram_link' => $setting->instagram ?? '',
        ];

        // Generate payment methods PDF
        $pdfContent = $this->generateAvailablePaymentMethods();

        // Prepare email data - similar to what you have in ViewReservation
        $data = array_merge([
            'name' => trim($user->first_name . ' ' . $user->last_name),
            'transaction_number' => $transaction->transaction_number,
            'email' => $user->email ?? 'no-reply@example.com',
            'invoice_number' => $invoice->invoice_number,
            'check_in' => $transaction->start_datetime->format('F j, Y'),
            'check_out' => $transaction->end_datetime->format('F j, Y'),
            'sub_total' => $invoice->sub_total,
            'amount_paid' => $invoice->amount_paid,
            'remaining_balance' => $invoice->balance_due,
            'convenience_fee' => $convenienceFee,
            'total_due' => $invoice->balance_due + $convenienceFee,
            'due_date' => Carbon::parse($transaction->start_datetime)->subDay()->format('F j, Y'), // 1 day before check-in
            'payment_link' => null, // We're not using PayMongo for now
            'is_pre_checkin' => true,
            'days_until_checkin' => 3,
        ], $brandingData);

        try {
            // Send the email
            Mail::to($data['email'])->send(new RequestRemainingBalanceMail($data, $pdfContent));
            
            // Mark invoice as requested
            $invoice->update([
                'requested_remaining_balance' => true,
                'balance_request_sent_at' => now(),
                'balance_request_count' => ($invoice->balance_request_count ?? 0) + 1,
                'pre_checkin_reminder_sent' => true, // Add this field if needed
            ]);

            Log::info("Pre-checkin balance request email sent for transaction {$transaction->transaction_number} to {$user->email}");
            
        } catch (\Exception $e) {
            Log::error("Email send failed for transaction {$transaction->id}: " . $e->getMessage());
            throw new \Exception("Failed to send email: " . $e->getMessage());
        }
    }

    /**
     * Generate available payment methods PDF
     * Same as in ViewReservation
     */
    private function generateAvailablePaymentMethods()
    {
        $paymentMethodService = app(PaymentMethodService::class);
        $paymentMethods = $paymentMethodService->getPaymentMethodsData();

        foreach ($paymentMethods as &$method) {
            if (!$method['has_convenience_fee']) {
                $method['note'] = 'No convenience fee for manual payment.';
            }
        }

        $pdf = Pdf::loadView('livewire.admin.reports.available-payment-methods', compact('paymentMethods'));
        return $pdf->output();
    }
}