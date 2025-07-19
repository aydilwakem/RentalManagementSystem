<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class PayMongoService
{
    public function createCheckoutSession(array $payload)
    {
        $client = new Client();
        $apiKey = env('PAYMONGO_SECRET_KEY');

        try {
            $response = $client->request('POST', 'https://api.paymongo.com/v1/checkout_sessions', [
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Basic ' . base64_encode($apiKey . ':'),
                ],
                'json' => $payload,
            ]);

            return json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            Log::error('PayMongo link creation failed: ' . $e->getMessage());
            return null;
        }
    }
}
