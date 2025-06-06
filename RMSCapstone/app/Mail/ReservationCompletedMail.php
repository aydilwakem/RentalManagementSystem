<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ReservationCompletedMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The reservation details.
     *
     * @var array
     */
    public $reservationData;


    /**
     * Create a new message instance.
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
        return $this->view('guest.emails.reservation-completed')
            ->subject('Reservation Completed!')
            ->with([
                'name' => $this->reservationData['name'],
                'email' => $this->reservationData['email'],
                'contact_number' => $this->reservationData['contact_number'],
                'transaction_number' => $this->reservationData['transaction_number'],
                'email' => $this->reservationData['email'],
                'invoice_number' => $this->reservationData['invoice_number'],
                'check_in' => $this->reservationData['check_in'],
                'check_out' => $this->reservationData['check_out'],
                'total_amount' => $this->reservationData['total_amount'],
                'deposit' => $this->reservationData['deposit'],
                'amount_paid' =>  $this->reservationData['amount_paid'],
                'balance_due' =>  $this->reservationData['balance_due'],
                'properties' => $this->reservationData['properties'],
                'activities' => $this->reservationData['activities'],

                //Branding
                'branding_company_name' => $this->reservationData['branding_company_name'],
                'logo_path' =>$this->reservationData['logo_path'],
                'branding_company_email' => $this->reservationData['branding_company_email'],
                'branding_company_contact' => $this->reservationData['branding_company_contact'],
                'company_address' => $this->reservationData['company_address'],
                'facebook_link' => $this->reservationData['facebook_link'],
                'instagram_link' => $this->reservationData['instagram_link'],
            ]);
    }
}
