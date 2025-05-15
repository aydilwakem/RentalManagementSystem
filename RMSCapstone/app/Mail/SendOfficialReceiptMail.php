<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendOfficialReceiptMail extends Mailable
{
    use Queueable, SerializesModels;

    public $pdfContent;
    public $receiptNumber;

    public function __construct($pdfContent, $receiptNumber)
    {
        $this->pdfContent = $pdfContent;
        $this->receiptNumber = $receiptNumber;
    }

    public function build()
    {
        return $this->subject('Your Official Receipt from Canopy Farm')
            ->view('guest.emails.official-receipt') // Create this view file
            ->attachData($this->pdfContent, 'official_receipt_' . $this->receiptNumber . '.pdf', [
                'mime' => 'application/pdf',
            ]);
    }
}
