<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewDayTourReservationMail extends Mailable
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
        return $this->view('guest.emails.new-daytour-reservation')
            ->subject('New Day Tour Reservation Notification')
            ->with([
                'name' => $this->reservationData['name'],
                'transaction_number' => $this->reservationData['transaction_number'],
                'email' => $this->reservationData['email'],
                'invoice_number' => $this->reservationData['invoice_number'],
                'tour_date' => $this->reservationData['tour_date'],
                'tour_name' => $this->reservationData['tour_name'],
                'rate_name' => $this->reservationData['rate_name'],
                'adult_count' => $this->reservationData['adult_count'],
                'kid_count' => $this->reservationData['kid_count'],
                'adult_rate' => $this->reservationData['adult_rate'],
                'kid_rate' => $this->reservationData['kid_rate'],
                'subtotal' => $this->reservationData['subtotal'],
                'convenience_fee' => $this->reservationData['convenience_fee'],
                'total_amount' => $this->reservationData['total_amount'],
                'payment_link' => $this->reservationData['payment_link'],
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
