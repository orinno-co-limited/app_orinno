<?php

namespace App\Services\Payment;

use Illuminate\Support\Facades\Log;

class MaxiCashService extends BasePaymentService
{
    private $apiUrl;

    public function __construct($method, $object)
    {
        parent::__construct($method, $object);
        // Define API URLs for live and test modes
        $this->apiUrl = $this->gateway->mode == GATEWAY_MODE_LIVE
            ? 'https://api.maxicashapp.com/PayEntry?data='
            : 'https://api-testbed.maxicashapp.com/PayEntry?data=';
    }

    public function makePayment($amount)
    {
        $this->setAmount($amount);

        // Generate unique order ID
        $paymentId = sha1(uniqid());
        $userId = auth()->id();

        // Prepare timestamp for signature
        $timestamp = time();

        // Generate signature using merchant password, user ID, order ID, and timestamp
        $signature = hash_hmac('sha256', $userId . '|' . $paymentId . '|' . $timestamp, $this->gateway->secret);

        // Encrypt user ID and timestamp
        $encryptedData = encrypt($userId . '|' . $timestamp);

        // Update callback URL with encrypted data and signature
        $callbackUrlWithData = $this->callbackUrl . '&data=' . urlencode($encryptedData) . '&signature=' . $signature;

        $response = [
            'success' => false,
            'redirect_url' => '',
            'payment_id' => '',
            'message' => __('Something went wrong')
        ];
        $this->setAmount($amount);
        $data = [
            'PayType' => 'MaxiCash',
            'Amount' => $this->amount * 100, // Convert amount to minor units (e.g., cents)
            'Currency' => $this->currency,
            'MerchantID' => $this->gateway->key,
            'MerchantPassword' => $this->gateway->secret,
            'Reference' => $paymentId,
            'Accepturl' => $callbackUrlWithData,
            'Cancelurl' => $this->cancelUrl,
            'Declineurl' => $this->cancelUrl
        ];

        try {
            // Convert data to JSON and create the redirect URL
            $dataJson = json_encode($data);
            $redirectUrl = $this->apiUrl . urlencode($dataJson);
            $response['payment_id'] = $paymentId;
            $response['redirect_url'] = $redirectUrl;
            $response['success'] = true;

        } catch (\Exception $e) {
            $response['message'] = $e->getMessage();
            Log::error('MaxiCash makePayment Error: ' . $e->getMessage());
        }

        return $response;
    }

    public function paymentConfirmation($order_id)
    {

        $data['success'] = false;
        $data['data'] = null;

        // Get signature and encrypted data from the request
        $signature = request()->query('signature');
        $encryptedData = request()->query('data');

        try {
            // Decrypt the passed data
            $decryptedData = decrypt($encryptedData);
            list($userId, $timestamp) = explode('|', $decryptedData);

            // Verify the signature by comparing it with the expected signature
            $expectedSignature = hash_hmac('sha256', $userId . '|' . $order_id . '|' . $timestamp, $this->gateway->secret);

            if (hash_equals($expectedSignature, $signature)) {
                $data['success'] = true;
                $data['data']['payment_status'] = 'success';
                $data['data']['payment_method'] = MAXICASH;
            } else {
                $data['message'] = 'Signature mismatch';
            }
        } catch (\Exception $e) {
            $data['message'] = 'Invalid or tampered data';
            Log::error('MaxiCash Payment Verification Failed: ' . $data['message']);
        }

        return $data;
    }
}
