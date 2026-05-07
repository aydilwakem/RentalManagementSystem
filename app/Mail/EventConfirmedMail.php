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

class EventConfirmedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $event;
    public $branding;
    /**
     * Create a new message instance.
     */
    public function __construct(Transaction $event, Setting $branding)
    {
        $this->event = $event; 
        $this->branding = $branding;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(){
        //Generate the pdf content for email attachment
        $pdf = Pdf::loadView('livewire.admin.events.event-details', [
            'event' => $this->event,
            'invoice' => $this->event->invoice,
            'payments' => $this->event->invoice->payments ?? [],
            'activities' => $this->event->activities,
            'services' => $this->event->services,
        ]);

         return $this->subject('Your Event is Confirmed!')
                    ->view('guest.emails.event-confirmed')
                    ->with([
                        'invoice.payments' => $this->event->invoice->payments, 
                        'properties' => $this->event->properties, 
                        'activities' => $this->event->activities,
                        'services' => $this->event->services,
                        'branding' => $this->branding,
                    ])
                    ->attachData($pdf->output(), 'event-details-' . $this->event->start_datetime . '.pdf', [
                        'mime' => 'application/pdf',
                    ]);
    }

    }

