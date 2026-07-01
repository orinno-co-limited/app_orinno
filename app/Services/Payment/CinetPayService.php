<?php

namespace App\Services\Payment;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CinetPayService extends BasePaymentService
{
    private $apiUrl;
    private $apiKey;
    private $siteId;

    public function __construct($method, $object)
    {
        parent::__construct($method, $object);
        $this->apiUrl = 'https://api-checkout.cinetpay.com/v2/payment';
        $this->apiKey = $this->gateway->key;
        $this->siteId = $this->gateway->secret;
    }

    public function makePayment($amount)
    {
        $this->setAmount($amount);

        $transaction_id = uniqid();

        // Prepare the payload according to CinetPay requirements
        $payload = [
            'amount' => (int)($this->amount*1),
            'currency' => $this->currency,
            'transaction_id' => $transaction_id,  // Generate a unique transaction ID
            'customer_name' => Auth::user()->name,
            'customer_email' => Auth::user()->email,
            'description' => 'Purchase',  // Set a purpose for the payment
            'return_url' => $this->callbackUrl,
            'notify_url' => $this->callbackUrl,  // Set the callback URL for payment notifications
            'site_id' => $this->siteId,
            'apikey' => $this->apiKey,
        ];

        // Make the request to CinetPay API
        $response = $this->curl_request($payload, $this->apiUrl, "POST");

        // Log the response for debugging
        Log::info(json_encode("payload"));
        Log::info(json_encode($payload));
        Log::info('<<<< CinetPay Response >>>>');
        Log::info(json_encode($response));

        $data['success'] = false;
        $data['redirect_url'] = '';
        $data['payment_id'] = '';
        $data['message'] = SOMETHING_WENT_WRONG;

        try {
            if ($response->code == 201) { // Check if the request was successful
                $data['redirect_url'] = $response->data->payment_url;
                $data['payment_id'] = $transaction_id;
                $data['success'] = true;
            }
            Log::info(json_encode($response));
            return $data;
        } catch (\Exception $ex) {
            $data['message'] = $ex->getMessage();
        }
        return $data;
    }

    public function paymentConfirmation($payment_id)
    {
        $data['success'] = false;
        $data['data'] = null;

        // Prepare the payload for verifying the payment
        $payload = [
            'transaction_id' => $payment_id,
            'site_id' => $this->siteId,
            'apikey' => $this->apiKey,
        ];

        $url = "https://api-checkout.cinetpay.com/v2/payment/check";

        // Make the request to CinetPay to verify the payment status
        $response = $this->curl_request($payload, $url, "POST");
        Log::info("res==========");
        Log::info(json_encode($response));
        // Check if the API response is successful
        if (isset($response->code)) {
            if ($response->data->status == 'ACCEPTED') {
                // Payment was successful
                $data['success'] = true;
                $data['data']['amount'] = $response->data->amount;
                $data['data']['currency'] = $response->data->currency;
                $data['data']['payment_status'] = 'success';
                $data['data']['payment_method'] = $response->data->payment_method;
                $data['data']['payment_date'] = $response->data->payment_date;
                $data['data']['operator_id'] = $response->data->operator_id;
            }
        } else {
            // Handle error responses
            $data['success'] = false;
            $data['message'] = $response->message ?? 'Failed to verify the transaction.';
        }
        Log::info("data =======");
        Log::info(json_encode($data));
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
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload)); // CinetPay requires JSON payload
        }
        $response = curl_exec($ch);
        curl_close($ch);
        $response = json_decode($response);
        return $response;
    }
}
