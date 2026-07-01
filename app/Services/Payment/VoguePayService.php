<?php

namespace App\Services\Payment;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class VoguePayService extends BasePaymentService
{
    private $merchantId;
    private $apiUrl;
    private $orderId;

    public function __construct($method, $object)
    {
        parent::__construct($method, $object);
        $this->merchantId = $this->gateway->key; // VoguePay's Merchant ID
        $this->apiUrl = 'https://voguepay.com/pay/';
        if (isset($object['id'])) {
            $this->orderId = $object['id'];
        }
    }

    public function makePayment($amount)
    {
        $this->setAmount($amount);

        // Transaction ID: Generate a unique reference for the transaction
        $transaction_id = sha1($this->orderId);

        // Prepare the payload for VoguePay
        $payload = [
            'v_merchant_id' => $this->merchantId,
            'merchant_ref' => $transaction_id,  // Unique transaction ID
            'memo' => 'Payment for purchase',  // Memo or description of the payment
            'total' => $this->amount,
            'cur' => $this->currency,  // Currency (e.g., NGN)
            'success_url' => $this->callbackUrl,  // Success URL after payment
            'fail_url' => $this->callbackUrl,  // Failure URL
            'notify_url' => $this->callbackUrl,  // Notification URL for payment status updates
            'buyer_email' => Auth::user()->email,  // Buyer's email
            'buyer_name' => Auth::user()->name,  // Buyer's name
        ];

        // Log the payload for debugging
        Log::info('<<<< VoguePay Request Payload >>>>');
        Log::info(json_encode($payload));

        // Redirect to VoguePay for payment
        $data['success'] = true;
        $data['redirect_url'] = $this->apiUrl . '?' . http_build_query($payload);
        $data['payment_id'] = $transaction_id;

        return $data;
    }

    public function paymentConfirmation($payment_id)
    {
        $data['success'] = false;
        $data['data'] = null;

        // VoguePay transaction verification URL
        $verificationUrl = "https://voguepay.com/?v_transaction_id=" . $payment_id . "&type=json";

        // Make the request to VoguePay to verify the payment status
        $response = $this->curl_request([], $verificationUrl);

        // Check the API response
        if (isset($response->status) && $response->status == 'Approved') {
            $data['success'] = true;
            $data['data']['amount'] = $response->total_paid_by_buyer;
            $data['data']['currency'] = $this->currency;
            $data['data']['payment_status'] = 'success';
            $data['data']['payment_method'] = VOGUEPAY;
            $data['data']['payment_date'] = $response->date;
            // You can store the transaction details in your database here
        } else {
            $data['message'] = $response->status_description ?? 'Payment not approved';
        }

        return $data;
    }

    public function curl_request($payload, $url, $method = 'GET')
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
        curl_setopt(
            $ch,
            CURLOPT_HTTPHEADER,
            array(
                "Content-Type: application/json"
            )
        );
        if ($method == 'POST') {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload)); // JSON payload
        }
        $response = curl_exec($ch);
        curl_close($ch);
        return json_decode($response);
    }
}
