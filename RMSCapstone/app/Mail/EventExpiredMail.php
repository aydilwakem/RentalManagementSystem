<?php

namespace App\Mail;

use App\Models\Setting;
use App\Models\Transaction;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EventExpiredMail extends Mailable
{
    use Queueable, SerializesModels;

    public $pending;
    public $branding;

    public function __construct(Transaction $pending, Setting $branding)
    {
        $this->pending = $pending; 
        $this->branding = $branding;
    }

    public function build(){
         return $this->subject('Your Event Booking Has Expired!')
                    ->view('guest.emails.event-expired')
                    ->with([
                        'invoice.payments' => $this->pending->invoice->payments, 
                        'properties' => $this->pending->properties, 
                        'activities' => $this->pending->activities,
                        'services' => $this->pending->services,
                        'branding' => $this->branding,
                    ]); 
    }

}
