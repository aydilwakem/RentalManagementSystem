<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ReservationConfirmedMail extends Mailable
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
        return $this->view('guest.emails.reservation-confirmed')
            ->subject('Reservation Confirmed!')
            ->with([
                'name' => $this->reservationData['name'],
                'email' => $this->reservationData['email'],
                'contact_number' => $this->reservationData['contact_number'],
                'transaction_number' => $this->reservationData['transaction_number'],
                'email' => $this->reservationData['email'],
                'check_in' => $this->reservationData['check_in'],
                'check_out' => $this->reservationData['check_out'],
                'requests' => $this->reservationData['requests'],
                'request_reply' => $this->reservationData['request_reply'],

                'deposit' => $this->reservationData['deposit'],

                'convenience_fee' => $this->reservationData['convenience_fee'],
                'invoice_number' => $this->reservationData['invoice_number'],
                'invoice_basesubtotal' =>  $this->reservationData['invoice_basesubtotal'],
                'invoice_total_discount' => $this->reservationData['invoice_total_discount'],
                'invoice_subtotal' => $this->reservationData['invoice_subtotal'],
                'amount_paid' =>  $this->reservationData['amount_paid'],
                'balance_due' =>  $this->reservationData['balance_due'],
                'total_amount' => $this->reservationData['total_amount'],

                'properties' => $this->reservationData['properties'],
                'activities' => $this->reservationData['activities'],
                'services' => $this->reservationData['services'],

                //Promo Code
                'code' => $this->reservationData['code'],
                'discount_type' => $this->reservationData['discount_type'],
                'discount_value' => $this->reservationData['discount_value'],


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
