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

    public function sendReservationEmails(array $reservationData, string $pdfContent): void
    {
        try {
            //email for guest
            Mail::to($reservationData['email'])->send(new ReservationSubmittedMail($reservationData, $pdfContent));
            Log::info('ReservationSubmittedMail sent to: ' . $reservationData['email']);

            //emails for admins
            Mail::to('username@try.com')->send(
                new NewReservationMail($reservationData)
            );
            Log::info('NewReservationMail sent to: username@try.com');

            Mail::to('rmscapstone26@gmail.com')->send(
                new NewReservationMail($reservationData)
            );
            Log::info('NewReservationMail also sent to: rmscapstone26@gmail.com');

        } catch (\Exception $e) {
            Log::error('Reservation email send failed: ' . $e->getMessage());
            throw $e;
        }
    }
}
