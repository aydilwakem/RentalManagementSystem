<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DayTouReservationCompletedMail extends Mailable
{
    use Queueable, SerializesModels;
    public $dayTourData;

    /**
     * Create a new message instance.
     */
    public function __construct($dayTourData)
    {
        $this->dayTourData = $dayTourData;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->view('guest.emails.daytour-completed')
            ->subject('Day Tour Reservation Completed!')
            ->with([
                'name' => $this->dayTourData['name'],
                'email' => $this->dayTourData['email'],
                'contact_number' => $this->dayTourData['contact_number'],

                'transaction_number' => $this->dayTourData['transaction_number'],
                'tour_date' => $this->dayTourData['tour_date'],
                'completion_date' => $this->dayTourData['completion_date'],
                'adult_count' => $this->dayTourData['adult_count'],
                'kid_count' => $this->dayTourData['kid_count'],
                'total_guests' => $this->dayTourData['total_guests'],
                'subtotal' => $this->dayTourData['subtotal'],
                'convenience_fee' => $this->dayTourData['convenience_fee'],
                'total_amount' => $this->dayTourData['total_amount'],

                'invoice_number' => $this->dayTourData['invoice_number'],
                'invoice_basesubtotal' => $this->dayTourData['invoice_basesubtotal'],
                'invoice_total_discount' => $this->dayTourData['invoice_total_discount'],
                'invoice_subtotal' => $this->dayTourData['invoice_subtotal'],
                'amount_paid' => $this->dayTourData['amount_paid'],
                'balance_due' => $this->dayTourData['balance_due'],

                'guest_details' => $this->dayTourData['guest_details'],

                // Branding
                'branding_company_name' => $this->dayTourData['branding_company_name'],
                'logo_path' => $this->dayTourData['logo_path'],
                'branding_company_email' => $this->dayTourData['branding_company_email'],
                'branding_company_contact' => $this->dayTourData['branding_company_contact'],
                'company_address' => $this->dayTourData['company_address'],
                'facebook_link' => $this->dayTourData['facebook_link'],
                'instagram_link' => $this->dayTourData['instagram_link'],
            ]);
    }
}
