<?php

namespace App\Services;

use App\Mail\RequestRemainingBalanceMail;
use App\Models\Setting;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class NotificationService
{
    public function sendRemainingBalanceEmail($transactionUser, $transaction, $invoice, $paymentLink, $pdfContent)
    {
        $setting = Setting::first();

        $data = [
            'name' => trim($transactionUser->first_name . ' ' . $transactionUser->last_name),
            'transaction_number' => $transaction->transaction_number,
            'email' => $transactionUser->email ?? 'no-reply@example.com',
            'invoice_number' => $invoice->invoice_number,
            'check_in' => $transaction->start_datetime->format('Y-m-d'),
            'check_out' => $transaction->end_datetime->format('Y-m-d'),
            'sub_total' => $invoice->sub_total,
            'amount_paid' => $invoice->amount_paid,
            'remaining_balance' => $invoice->balance_due,
            'payment_link' => $paymentLink,

            // Branding
            'branding_company_name' => $setting->company_name,
            'logo_path' => $setting->logo,
            'branding_company_email' => $setting->email,
            'branding_company_contact' => $setting->contact_number,
            'company_address' => $setting->address,
            'facebook_link' => $setting->facebook,
            'instagram_link' => $setting->instagram,
        ];

        try {
            Mail::to($data['email'])->send(new RequestRemainingBalanceMail($data, $pdfContent));
        } catch (\Exception $e) {
            Log::error('Email send failed: ' . $e->getMessage());
            throw new \Exception('Reservation saved, but confirmation email failed to send.');
        }
    }
}
