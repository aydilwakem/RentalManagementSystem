<?php

namespace App\Mail;


use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RequestRemainingBalanceMail extends Mailable
{
    use Queueable, SerializesModels;

    public $reservationData;
    public $pdfContent;



    /**
     * Create a new message instance.
     */
    public function __construct($reservationData, $pdfContent)
    {
        $this->reservationData = $reservationData;
        $this->pdfContent = $pdfContent;
    }


    public function build()
    {
        return $this->view('guest.emails.request-remaining-balance')
            ->subject('Request Remaining Balance')
            ->with([
                'name' => $this->reservationData['name'],
                'transaction_number' => $this->reservationData['transaction_number'],
                'email' => $this->reservationData['email'],
                'invoice_number' => $this->reservationData['invoice_number'],
                'check_in' => $this->reservationData['check_in'],
                'check_out' => $this->reservationData['check_out'],
                'sub_total' => $this->reservationData['sub_total'],
                'amount_paid' => $this->reservationData['amount_paid'],
                'remaining_balance' => $this->reservationData['remaining_balance'],
                'payment_link' =>  $this->reservationData['payment_link'],

                //Branding
                'branding_company_name' => $this->reservationData['branding_company_name'],
                'logo_path' =>$this->reservationData['logo_path'],
                'branding_company_email' => $this->reservationData['branding_company_email'],
                'branding_company_contact' => $this->reservationData['branding_company_contact'],
                'company_address' => $this->reservationData['company_address'],
                'facebook_link' => $this->reservationData['facebook_link'],
                'instagram_link' => $this->reservationData['instagram_link'],
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
