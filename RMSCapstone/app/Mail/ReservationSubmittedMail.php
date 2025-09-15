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
    public $pdfContent;

    /**
     * Create a new message instance.
     *
     * @param  array  $reservationData
     * @param  string  $pdfContent
     * @return void
     */
    public function __construct($reservationData, $pdfContent)
    {
        $this->reservationData = $reservationData;
        $this->pdfContent = $pdfContent;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('guest.emails.reservation-submitted')
            ->subject('Reservation Successfully Submitted')
            ->with([
                'name' => $this->reservationData['name'],
                'transaction_number' => $this->reservationData['transaction_number'],
                'email' => $this->reservationData['email'],
                'check_in' => $this->reservationData['check_in'],
                'check_out' => $this->reservationData['check_out'],
                'expirationHours' => $this->reservationData['expirationHours'],
                'payment_link' =>  $this->reservationData['payment_link'],



                'base_subtotal' => $this->reservationData['base_subtotal'],
                'promo_code' => $this->reservationData['promo_code'],
                'promo_amount' => $this->reservationData['promo_amount'],
                'subtotal' => $this->reservationData['subtotal'],
                'convenience_fee' => $this->reservationData['convenience_fee'],
                'total_amount' => $this->reservationData['total_amount'],
                'deposit' => $this->reservationData['deposit'],


                'invoice_number' => $this->reservationData['invoice_number'],


                //Branding
                'branding_company_name' => $this->reservationData['branding_company_name'],
                'logo_path' => $this->reservationData['logo_path'],
                'branding_company_email' => $this->reservationData['branding_company_email'],
                'branding_company_contact' => $this->reservationData['branding_company_contact'],
                'company_address' => $this->reservationData['company_address'],
                'facebook_link' => $this->reservationData['facebook_link'],
                'instagram_link' => $this->reservationData['instagram_link'],
                'cart_items' => $this->reservationData['cart_items'],

                //Payment Methods
                //'payment_methods'         => $this->reservationData['payment_methods'],
            ])
            ->attachData(
                $this->pdfContent, 
                'Available_Payment_Methods.pdf', 
                [
                    'mime' => 'application/pdf',
                ]
            );
    }
}
