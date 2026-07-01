<?php

namespace App\Services\Payment;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PayHerePaymentService extends BasePaymentService
{
    private $apiUrl;
    private $merchantId;
    private $merchantSecret;
    private $orderId;

    public function __construct($method, $object)
    {
        parent::__construct($method, $object);

        if (isset($object['id'])) {
            $this->orderId = $object['id'];
        }

        // Fetching gateway details from the parent class (BasePaymentService)
        $this->apiUrl = $this->gateway->mode == GATEWAY_MODE_LIVE
            ? 'https://www.payhere.lk/pay/checkout'
            : 'https://sandbox.payhere.lk/pay/checkout';

        $this->merchantId = $this->gateway->key; // Your Merchant ID
        $this->merchantSecret = $this->gateway->secret; // Your Merchant Secret
    }

    public function makePayment($amount)
    {
        $this->setAmount($amount);

        // Generate order_id, can be a unique identifier for this order
        $paymentId = sha1($this->orderId);
        $userId = auth()->id();

        // Prepare timestamp
        $timestamp = time();

        // Generate the signature (HMAC with secret key, including user ID, order ID, and timestamp)
        $signature = hash_hmac('sha256', $userId . '|' . $paymentId . '|' . $timestamp, $this->merchantSecret);

        // Encrypt auth()->id() and order_id
        $encryptedData = encrypt($userId . '|' . $timestamp);

        // Update callback URL with encrypted data and signature
        $callbackUrlWithData = $this->callbackUrl . '&data=' . urlencode($encryptedData) . '&signature=' . $signature;

        // Prepare the payload for PayHere payment
        $payload = [
            'merchant_id' => $this->merchantId,
            'return_url' => $callbackUrlWithData, // Use the modified callback URL
            'cancel_url' => $this->cancelUrl,
            'notify_url' => null,
            'order_id' => $paymentId, // Unique order ID
            'items' => 'Order Payment', // Order description
            'currency' => $this->currency,
            'amount' => $this->amount,
            'first_name' => Auth::user()->first_name,
            'last_name' => Auth::user()->last_name,
            'email' => Auth::user()->email,
            'phone' => Auth::user()->phone,
            'address' => Auth::user()->address,
            'city' => Auth::user()->city,
            'country' => Auth::user()->country,
            'hash' => $this->generateHash($paymentId, $this->amount),
        ];

        // Generate the HTML form for the payment
        $formHtml = $this->generatePaymentForm($payload);

        // Get the previous URL
        $previousUrl = url()->previous();

        session()->flash('payment_form_html', $formHtml);

        // Return URL with the form HTML embedded
        $data['redirect_url'] = $previousUrl;
        $data['payment_id'] = $paymentId;
        $data['success'] = true;
        $data['message'] = 'Redirecting to payment...';

        return $data;
    }

    // Generate the hash using the merchant ID, order ID, amount, and currency
    private function generateHash($order_id, $amount)
    {
        $hash_value = strtoupper(
            md5(
                $this->merchantId .
                $order_id .
                number_format($amount, 2, '.', '') .
                $this->currency .
                strtoupper(md5($this->merchantSecret))
            )
        );

        return $hash_value;
    }

    /**
     * Verify the payment using the access token and order_id.
     */
    public function paymentConfirmation($order_id)
    {
        $data['success'] = false;
        $data['data'] = null;

        $signature = request()->query('signature');
        $encryptedData = request()->query('data');

        try {
            // Decrypt the passed data
            $decryptedData = decrypt($encryptedData);
            list($userId, $timestamp) = explode('|', $decryptedData);

            // Verify the signature (same as before)
            $expectedSignature = hash_hmac('sha256', $userId . '|' . $order_id . '|' . $timestamp, $this->merchantSecret);

            if (hash_equals($expectedSignature, $signature)) {
                $data['success'] = true;
                $data['data']['payment_status'] = 'success';
                $data['data']['payment_method'] = 'PAYHERE';
            } else {
                $data['message'] = 'Signature mismatch';
            }
        } catch (\Exception $e) {
            // Handle decryption failure
            $data['message'] = 'Invalid or tampered data';
        }

        return $data;
    }

    private function generatePaymentForm($payload)
    {
        $form = '<form method="post" action="' . $this->apiUrl . '">';
        foreach ($payload as $key => $value) {
            $form .= '<input type="hidden" name="' . $key . '" value="' . htmlspecialchars($value) . '">';
        }
        $form .= '<input type="submit" value="Pay Now">';
        $form .= '</form>';

        return $form;
    }
}
