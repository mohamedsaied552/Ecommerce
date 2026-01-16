<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PaymobController extends Controller
{
    public function pay(Request $request)
    {
        $totalCents = $request->total_cents; // from checkout

        // Optional: validate the total
        if (!$totalCents || $totalCents <= 0) {
            return redirect()->back()->withErrors(['total_cents' => 'Invalid amount']);
        }

        // ⚡ Guzzle/HTTP client with SSL disabled (for local dev only)
        $http = Http::withOptions([
            'verify' => false, // bypass SSL verification locally
            'timeout' => 30,   // optional: timeout
        ]);

        // 1️⃣ Authentication
        $auth = $http->post(config('paymob.base_url') . '/auth/tokens', [
            'api_key' => config('paymob.api_key'),
        ])->json();

        if (!isset($auth['token'])) {
            return redirect()->back()->withErrors(['paymob' => 'Failed to authenticate with Paymob']);
        }

        $token = $auth['token'];

        // 2️⃣ Create Order
        $order = $http->post(config('paymob.base_url') . '/ecommerce/orders', [
            'auth_token' => $token,
            'delivery_needed' => false,
            'amount_cents' => $totalCents,
            'currency' => 'EGP',
            'items' => [],
        ])->json();

        if (!isset($order['id'])) {
            return redirect()->back()->withErrors(['paymob' => 'Failed to create order']);
        }

        // 3️⃣ Payment Key
        $paymentKey = $http->post(config('paymob.base_url') . '/acceptance/payment_keys', [
            'auth_token' => $token,
            'amount_cents' => $totalCents,
            'expiration' => 3600,
            'order_id' => $order['id'],
            'billing_data' => [
                'first_name' => 'Test',
                'last_name' => 'User',
                'email' => 'test@test.com',
                'phone_number' => '01000000000',
                'apartment' => 'NA',
                'floor' => 'NA',
                'street' => 'NA',
                'building' => 'NA',
                'shipping_method' => 'NA',
                'postal_code' => 'NA',
                'city' => 'Cairo',
                'country' => 'EG',
                'state' => 'Cairo',
            ],
            'currency' => 'EGP',
            'integration_id' => config('paymob.integration_id'),
        ])->json();

        if (!isset($paymentKey['token'])) {
            return redirect()->back()->withErrors(['paymob' => 'Failed to generate payment key']);
        }

        // 4️⃣ Redirect to Paymob iframe
        $iframeUrl = "https://accept.paymob.com/api/acceptance/iframes/"
            . config('paymob.iframe_id')
            . "?payment_token=" . $paymentKey['token'];

        return redirect()->away($iframeUrl);
    }
}
