<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class PayMongoService
{
    protected $client;
    protected $apiKey;
    protected $baseUrl = 'https://api.paymongo.com/v1';

    public function __construct()
    {
        $this->client = new Client([
            'timeout' => 30,
            'connect_timeout' => 10,
        ]);
        $this->apiKey = env('PAYMONGO_SECRET_KEY');
    }

    public function createCheckoutSession(array $payload)
    {
        $cacheKey = 'paymongo_session_' . md5(serialize($payload));

        return Cache::remember($cacheKey, 300, function () use ($payload) {
            try {
                $response = $this->client->request('POST', $this->baseUrl . '/checkout_sessions', [
                    'headers' => $this->getHeaders(),
                    'json' => $payload,
                ]);

                $result = json_decode($response->getBody(), true);

                Log::info('PayMongo checkout session created', [
                    'session_id' => $result['data']['id'] ?? 'unknown'
                ]);

                return $result;
            } catch (RequestException $e) {
                $this->handleRequestException($e, 'Checkout session creation failed');
                return null;
            } catch (\Exception $e) {
                Log::error(' Unexpected error creating PayMongo session: ' . $e->getMessage());
                return null;
            }
        });
    }

    public function retrievePayment($paymentId)
    {
        try {
            $response = $this->client->request('GET', $this->baseUrl . '/payments/' . $paymentId, [
                'headers' => $this->getHeaders(),
            ]);

            return json_decode($response->getBody(), true);
        } catch (RequestException $e) {
            $this->handleRequestException($e, 'Payment retrieval failed');
            return null;
        }
    }

    private function getHeaders()
    {
        return [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Basic ' . base64_encode($this->apiKey . ':'),
        ];
    }

    private function handleRequestException(RequestException $e, $context)
    {
        if ($e->hasResponse()) {
            $statusCode = $e->getResponse()->getStatusCode();
            $responseBody = $e->getResponse()->getBody()->getContents();

            Log::error($context, [
                'status_code' => $statusCode,
                'response' => $responseBody,
                'message' => $e->getMessage()
            ]);
        } else {
            Log::error($context . ': No response received', [
                'message' => $e->getMessage()
            ]);
        }
    }
}
