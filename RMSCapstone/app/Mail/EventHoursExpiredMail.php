<?php

namespace App\Mail;

use App\Models\Setting;
use App\Models\Transaction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EventHoursExpiredMail extends Mailable
{
    use Queueable, SerializesModels;

    public $eventTransaction;
    public $settings;


    public function __construct(Transaction $eventTransaction, Setting $settings)
    {
        $this->eventTransaction = $eventTransaction; 
        $this->settings = $settings;
    }

   public function build(){
        return $this->subject('Your Event Booking Has Expired!')
                    ->view('guest.emails.event-hours-expired')
                    ->with([
                        'invoice.payments' => $this->eventTransaction->invoice->payments, 
                        'properties' => $this->eventTransaction->properties, 
                        'activities' => $this->eventTransaction->activities,
                        'services' => $this->eventTransaction->services,
                        'settings' => $this->settings,
                    ]); 
   }
}
