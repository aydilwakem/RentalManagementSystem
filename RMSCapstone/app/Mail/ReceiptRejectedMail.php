<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ReceiptRejectedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $paymentDetails;
    /**
     * Create a new message instance.
     */
    public function __construct($paymentDetails)
    {
        $this->paymentDetails = $paymentDetails;
    }

    public function build()
    {
        return $this->subject('Your payment receipt was rejected')
            ->view('emails.receipt-rejected');
    }
}
