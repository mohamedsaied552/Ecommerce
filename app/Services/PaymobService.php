<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PaymobService
{
    private string $apiKey;
    private int $integrationId;
    private int $iframeId;
    private string $baseUrl = 'https://accept.paymob.com/api';

    public function __construct()
    {
        $this->apiKey = (string) config('services.paymob.api_key');
        $this->integrationId = (int) config('services.paymob.integration_id');
        $this->iframeId = (int) config('services.paymob.iframe_id');

        if (empty($this->apiKey) || $this->integrationId <= 0 || $this->iframeId <= 0) {
            throw new \Exception('Paymob credentials are not configured correctly. Please check your .env file.');
        }
    }

    public function authenticate(): string
    {
        $response = Http::post("{$this->baseUrl}/auth/tokens", [
            'api_key' => $this->apiKey,
        ]);

        if (!$response->successful()) {
            Log::error('Paymob authentication failed', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
            throw new \Exception('Failed to authenticate with Paymob: ' . $response->body());
        }

        $data = $response->json();
        if (!isset($data['token'])) {
            throw new \Exception('Invalid response from Paymob authentication');
        }

        return $data['token'];
    }

    public function createOrder(string $authToken, int $amountCents, string $merchantOrderId, array $items = []): array
    {
        $response = Http::post("{$this->baseUrl}/ecommerce/orders", [
            'auth_token' => $authToken,
            'delivery_needed' => false,
            'amount_cents' => $amountCents,
            'currency' => 'EGP',
            'merchant_order_id' => $merchantOrderId,
            'items' => $items,
        ]);

        if (!$response->successful()) {
            Log::error('Paymob order creation failed', [
                'status' => $response->status(),
                'body' => $response->body(),
                'merchant_order_id' => $merchantOrderId,
            ]);
            throw new \Exception('Failed to create Paymob order: ' . $response->body());
        }

        $data = $response->json();
        if (!isset($data['id'])) {
            throw new \Exception('Invalid response from Paymob order creation');
        }

        return $data;
    }

    public function generatePaymentKey(
        string $authToken,
        int $orderId,
        int $amountCents,
        array $billingData
    ): string {
        // Paymob requires a complete billing_data object in many cases.
        $billingData = array_merge([
            'apartment' => 'NA',
            'email' => 'na@example.com',
            'floor' => 'NA',
            'first_name' => 'Customer',
            'street' => 'NA',
            'building' => 'NA',
            'phone_number' => '+201000000000',
            'shipping_method' => 'NA',
            'postal_code' => '00000',
            'city' => 'Cairo',
            'country' => 'EG',
            'last_name' => 'NA',
            'state' => 'Cairo',
        ], $billingData);

        $response = Http::post("{$this->baseUrl}/acceptance/payment_keys", [
            'auth_token' => $authToken,
            'amount_cents' => $amountCents,
            'expiration' => 3600,
            'order_id' => $orderId,
            'billing_data' => $billingData,
            'currency' => 'EGP',
            'integration_id' => $this->integrationId,
        ]);

        if (!$response->successful()) {
            Log::error('Paymob payment key generation failed', [
                'status' => $response->status(),
                'body' => $response->body(),
                'order_id' => $orderId,
            ]);
            throw new \Exception('Failed to generate Paymob payment key: ' . $response->body());
        }

        $data = $response->json();
        if (!isset($data['token'])) {
            throw new \Exception('Invalid response from Paymob payment key generation');
        }

        return $data['token'];
    }

    public function getCheckoutUrl(string $paymentKey): string
    {
        return "https://accept.paymob.com/api/acceptance/iframes/{$this->iframeId}?payment_token={$paymentKey}";
    }

    // Keep invoice flow working
    public function getCheckoutUrlForInvoice(int $amountCents, string $merchantOrderId, array $billingData = []): string
    {
        $authToken = $this->authenticate();
        $order = $this->createOrder($authToken, $amountCents, $merchantOrderId, []);
        $paymentKey = $this->generatePaymentKey($authToken, (int)$order['id'], $amountCents, $billingData);

        return $this->getCheckoutUrl($paymentKey);
    }

    // New: Order flow for Store
    public function getCheckoutUrlForStoreOrder(int $amountCents, string $merchantOrderId, array $billingData, array $items): string
    {
        $authToken = $this->authenticate();
        $order = $this->createOrder($authToken, $amountCents, $merchantOrderId, $items);
        $paymentKey = $this->generatePaymentKey($authToken, (int)$order['id'], $amountCents, $billingData);

        return $this->getCheckoutUrl($paymentKey);
    }

    public function verifyHmac(array $data, string $hmac): bool
    {
        $hmacSecret = config('services.paymob.hmac_secret');
        if (empty($hmacSecret)) {
            return false;
        }

        $order = $data['obj']['order'] ?? [];
        $stringToHash = '';

        foreach (['id','created_at','merchant_order_id','amount_cents','currency','is_paid'] as $k) {
            if ($k === 'is_paid' && isset($order[$k])) {
                $stringToHash .= $order[$k] ? 'true' : 'false';
            } elseif (isset($order[$k])) {
                $stringToHash .= $order[$k];
            }
        }

        $calculatedHmac = hash_hmac('sha512', $stringToHash, $hmacSecret);

        return hash_equals($calculatedHmac, $hmac);
    }
}
