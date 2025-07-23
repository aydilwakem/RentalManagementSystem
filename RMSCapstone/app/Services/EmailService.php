<?php

namespace App\Services;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\SendOfficialReceiptMail;
use App\Mail\RequestRemainingBalanceMail;
use App\Mail\PaymentUploadedMail;
use App\Mail\ReservationSubmittedMail;
use App\Mail\NewReservationMail;

class EmailService
{
    public function sendOfficialReceipt(string $to, string $pdfContent, string $receiptNumber, $user, array $data): void
    {
        Mail::to($to)->send(new SendOfficialReceiptMail($pdfContent, $receiptNumber, $user, $data));
    }

    public function sendPaymentUploadedMail(string $to, array $paymentDetails): void
    {
        Mail::to($to)->send(new PaymentUploadedMail($paymentDetails));
    }

    public function sendReservationEmails(array $reservationData): void
    {
        try {
            Mail::to($reservationData['email'])->send(new ReservationSubmittedMail($reservationData));
            Log::info('ReservationSubmittedMail sent to: ' . $reservationData['email']);

            Mail::to('rmscapstone26@gmail.com')->send(new NewReservationMail($reservationData));
            Log::info('NewReservationMail sent to: rmscapstone26@gmail.com');
        } catch (\Exception $e) {
            Log::error('Reservation email send failed: ' . $e->getMessage());
            throw $e;
        }
    }
}
