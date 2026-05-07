<?php

namespace App\Services;

use App\Models\PaymentMethod;

class PaymentMethodService
{
    /**
     * Fetch payment method data
     */
    public function getPaymentMethodsData(): array
    {
        return PaymentMethod::all()->map(function ($method) {
            return [
                'mode_of_payment_name' => $method->mode_of_payment_name,
                'account_name'         => $method->account_name,
                'account_number'       => $method->account_number,
                'qr_image'             => $method->mode_of_payment_qr_image,
                // Only online methods (not Cash) have convenience fee
                'has_convenience_fee'  => strtolower($method->mode_of_payment_name) !== 'cash',
            ];
        })->toArray();
    }
}
