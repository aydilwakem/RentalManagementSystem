<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;


class PaymentUploadedMail extends Mailable
{
    use Queueable, SerializesModels;


    /**
     * The payment details.
     *
     * @var array
     */
    public $paymentDetails;

    /**
     * Create a new message instance.
     *
     * @param  array  $paymentDetails
     * @return void
     */
    public function __construct($paymentDetails)
    {
        $this->paymentDetails = $paymentDetails;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('guest.emails.payment-uploaded')
            ->subject('Payment Submitted Successfully')
            ->with([
                'full_name' => $this->paymentDetails['full_name'],
                'email' => $this->paymentDetails['email'],
                'payment_method_id' => $this->paymentDetails['payment_method_id'],
                'check_in' => $this->paymentDetails['check_in'],
                'check_out' => $this->paymentDetails['check_out'],
                'total_amount' => $this->paymentDetails['total_amount'],
                'deposit' => $this->paymentDetails['deposit'],

                // branding details
                'branding_company_name' => $this->paymentDetails['branding_company_name'] ?? 'Canopy Farm',
                'logo_path' => $this->paymentDetails['logo_path'] ?? '',
                'branding_company_email' => $this->paymentDetails['branding_company_email'] ?? '',
                'branding_company_contact' => $this->paymentDetails['branding_company_contact'] ?? '',
                'company_address' => $this->paymentDetails['company_address'] ?? '',
                'facebook_link' => $this->paymentDetails['facebook_link'] ?? '#',
                'instagram_link' => $this->paymentDetails['instagram_link'] ?? '#',
            ]);
    }
}
