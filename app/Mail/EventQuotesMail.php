<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EventQuotesMail extends Mailable
{
    use Queueable, SerializesModels;

    public $quoteData;

     public function __construct($quoteData)
    {
        $this->quoteData = $quoteData;
    }


    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }

    public function build()
    {
         return $this->subject('New Event Quote')
                ->view('guest.emails.event-quotes')
                ->with(['quoteData' => $this->quoteData]);
    }
}
