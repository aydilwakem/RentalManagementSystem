<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewReservationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $reservationData;

    /**
     * Create a new message instance.
     */
    public function __construct($reservationData)
    {
        $this->reservationData = $reservationData;
    }

    
    public function build()
    {
        return $this->view('guest.emails.new-reservation')
            ->subject('New Reservation Notification')
            ->with([
                'name' => $this->reservationData['name'],
                'transaction_number' => $this->reservationData['transaction_number'],
                'email' => $this->reservationData['email'],
                'invoice_number' => $this->reservationData['invoice_number'],
                'check_in' => $this->reservationData['check_in'],
                'check_out' => $this->reservationData['check_out'],
                'total_amount' => $this->reservationData['total_amount'],
                'deposit' => $this->reservationData['deposit'],

                //All rooms and activities:
                'cart_items' => $this->reservationData['cart_items'],

                //Financial Summary
                'promo_code' => $this->reservationData['promo_code'],
                'promo_amount' => $this->reservationData['promo_amount'],
                'base_subtotal' => $this->reservationData['base_subtotal'],
                'subtotal' => $this->reservationData['subtotal'],
                'convenience_fee' => $this->reservationData['convenience_fee'],
                'total_amount' => $this->reservationData['total_amount'],
                'total_payable_amount' => $this->reservationData['total_payable_amount'],

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
