<?php

namespace App\Services;

use Illuminate\Support\Facades\Mail;
use App\Mail\SendOfficialReceiptMail;
use App\Mail\RequestRemainingBalanceMail;

class EmailService
{
    public function sendOfficialReceipt(string $to, string $pdfContent, string $receiptNumber, $user, array $data): void
    {
        Mail::to($to)->send(new SendOfficialReceiptMail($pdfContent, $receiptNumber, $user, $data));
    }
}
