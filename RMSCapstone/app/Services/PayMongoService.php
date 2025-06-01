<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class PayMongoService
{
    protected $secret;

    public function __construct()
    {
        $this->secret = config('services.paymongo.secret');
    }

    public function createGcashPaymentIntent($amount, $description = 'Test Payment')
    {
        // Step 1: Payment Intent
        $intent = Http::withBasicAuth($this->secret, '')
            ->post('https://api.paymongo.com/v1/payment_intents', [
                'data' => [
                    'attributes' => [
                        'amount' => $amount,
                        'payment_method_allowed' => ['gcash'],
                        'payment_method_options' => ['gcash'],
                        'currency' => 'PHP',
                        'description' => $description
                    ]
                ]
            ])
            ->json();

        $intentId = $intent['data']['id'];
        $clientKey = $intent['data']['attributes']['client_key'];

        // Step 2: Payment Method
        $method = Http::withBasicAuth($this->secret, '')
            ->post('https://api.paymongo.com/v1/payment_methods', [
                'data' => [
                    'attributes' => [
                        'type' => 'gcash',
                        'amount' => $amount,
                        'currency' => 'PHP'
                    ]
                ]
            ])
            ->json();

        $methodId = $method['data']['id'];

        // Step 3: Attach
        $attach = Http::withBasicAuth($this->secret, '')
            ->post("https://api.paymongo.com/v1/payment_intents/$intentId/attach", [
                'data' => [
                    'attributes' => [
                        'payment_method' => $methodId,
                        'client_key' => $clientKey
                    ]
                ]
            ])
            ->json();

        return $attach['data']['attributes']['next_action']['redirect']['url'] ?? null;
    }
}
