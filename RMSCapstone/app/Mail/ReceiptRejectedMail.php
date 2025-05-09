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

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Payment Rejected')
            ->view('guest.emails.payment-rejected')
            ->with([
                'rejection_reason' => $this->paymentDetails['rejection_reason'],
                'user_email' => $this->paymentDetails['user_email'],
                'first_name' => $this->paymentDetails['first_name'],
                'last_name' => $this->paymentDetails['last_name'],
            ]);
    }
}
