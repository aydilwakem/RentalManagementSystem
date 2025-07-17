<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;


class ReservationSubmittedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $reservationData;

    /**
     * Create a new message instance.
     *
     * @param  array  $reservationData
     * @return void
     */
    public function __construct($reservationData)
    {
        $this->reservationData = $reservationData;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('guest.emails.reservation-submitted')
            ->subject('Reservation Confirmation')
            ->with([
                'name' => $this->reservationData['name'],
                'transaction_number' => $this->reservationData['transaction_number'],
                'email' => $this->reservationData['email'],
                'invoice_number' => $this->reservationData['invoice_number'],
                'check_in' => $this->reservationData['check_in'],
                'check_out' => $this->reservationData['check_out'],
                'total_amount' => $this->reservationData['total_amount'],
                'deposit' => $this->reservationData['deposit'],
                'expirationHours' => $this->reservationData['expirationHours'],
                'payment_link' =>  $this->reservationData['payment_link'],
                //Branding
                'branding_company_name' => $this->reservationData['branding_company_name'],
                'logo_path' => $this->reservationData['logo_path'],
                'branding_company_email' => $this->reservationData['branding_company_email'],
                'branding_company_contact' => $this->reservationData['branding_company_contact'],
                'company_address' => $this->reservationData['company_address'],
                'facebook_link' => $this->reservationData['facebook_link'],
                'instagram_link' => $this->reservationData['instagram_link'],

            ]);
    }
}
