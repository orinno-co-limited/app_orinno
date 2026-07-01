<?php

namespace App\Services\Payment;

use Illuminate\Support\Facades\Http;

class PaymobService extends BasePaymentService
{
    private $apiUrl;
    private $apiKey;
    private $integrationId;

    public function __construct($method, $object)
    {
        parent::__construct($method, $object);
        $this->apiUrl = 'https://accept.paymobsolutions.com/api';
        $this->apiKey = $this->gateway->key;
        $this->integrationId = $this->gateway->secret;
    }

    public function authenticate()
    {
        $response = Http::post($this->apiUrl . '/auth/tokens', [
            'api_key' => $this->apiKey,
        ]);

        $data = $response->json();
        return $data['token'] ?? null;
    }

    public function createOrder($amount)
    {
        $authToken = $this->authenticate();

        $orderData = [
            'auth_token' => $authToken,
            'delivery_needed' => false,
            'amount_cents' => $amount * 100, // Convert to cents
            'currency' => $this->currency,
            'merchant_order_id' => uniqid(), // Your unique order ID
            'items' => [], // Array of items for the order (optional)
        ];

        $response = Http::post($this->apiUrl . '/ecommerce/orders', $orderData);
        $order = $response->json();

        return $order['id'] ?? null;
    }

    public function generatePaymentKey($orderId, $amount)
    {
        $authToken = $this->authenticate();

        $paymentKeyData = [
            'auth_token' => $authToken,
            'amount_cents' => $amount * 100, // Convert to cents
            'expiration' => 3600, // Payment key expiration in seconds
            'order_id' => $orderId,
            'billing_data' => [
                'email' => auth()->user()->email,
                'first_name' => auth()->user()->name ?? 'name',
                'last_name' => auth()->user()->name ?? 'name',
                'phone_number' => auth()->user()->mobile ?? '0123456789',
                'city' => auth()->user()->city ?? 'Cairo',
                'country' => auth()->user()->country ?? 'EG',
                'street' => auth()->user()->address ?? '123 Street',
                'building' => auth()->user()->address ?? '123 Street',
                'floor' => auth()->user()->address ?? '123 Street',
                'apartment' => auth()->user()->address ?? '123 Street',
            ],
            'currency' => $this->currency,
            'integration_id' => $this->integrationId,
        ];

        $response = Http::post($this->apiUrl . '/acceptance/payment_keys', $paymentKeyData);
        $paymentKey = $response->json();

        return $paymentKey['token'] ?? null;
    }

    public function makePayment($amount)
    {
        $orderId = $this->createOrder($amount);

        if (!$orderId) {
            return [
                'success' => false,
                'message' => 'Failed to create order',
            ];
        }

        $paymentKey = $this->generatePaymentKey($orderId, $amount);

        if (!$paymentKey) {
            return [
                'success' => false,
                'message' => 'Failed to generate payment key',
            ];
        }

        return [
            'success' => true,
            'redirect_url' => "https://accept.paymobsolutions.com/api/acceptance/iframes/{$this->integrationId}?payment_token={$paymentKey}",
        ];
    }

    public function paymentConfirmation($paymentId)
    {
        $response = Http::get($this->apiUrl . '/acceptance/transactions/' . $paymentId);

        $data['success'] = false;
        $data['data'] = null;

        if ($response->successful()) {
            $paymentData = $response->json();

            if ($paymentData['success'] == true) {
                $data['success'] = true;
                $data['data'] = [
                    'payment_status' => 'success',
                    'payment_method' => PAYMOB
                ];
            }
        }else{
            $data['message'] = 'Payment not approved';
        }

        return $data;
    }
}
