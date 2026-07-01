<?php

namespace App\Services\Payment;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class ZitoPayService extends BasePaymentService
{
    private $apiUrl;
    private $username;
    private $memo;

    public function __construct($method, $object)
    {
        parent::__construct($method, $object);
        $this->apiUrl = 'https://zitopay.africa'; // API endpoint for ZitoPay
        $this->username = $this->gateway->key; // Assuming ZitoPay username is stored in the gateway object
        $this->memo = Str::random(16);
    }

    public function makePayment($amount)
    {
        $this->setAmount($amount); // Set the amount in the base class

        // Prepare the payload for ZitoPay payment request
        $payload = [
            'currency' => $this->currency,
            'amount' => $this->amount,
            'ref' => $this->memo,
            'memo' => $this->memo,
            'receiver' => $this->username,
            'success_url' => $this->callbackUrl,
            'cancel_url' => $this->callbackUrl,
        ];

        $data['success'] = false;
        $data['redirect_url'] = '';
        $data['payment_id'] = '';
        $data['message'] = 'Something went wrong.';

        try {
            // URL-encode the payload array as a query string
            $query = http_build_query($payload);
            // Append the query string to the API URL
            $redirectUrl = $this->apiUrl . '/sci?' . $query;

            // Create the redirect URL for ZitoPay checkout
            $data['redirect_url'] = $redirectUrl;
            $data['payment_id'] = $this->memo;
            $data['success'] = true;

            return $data;
        } catch (\Exception $ex) {
            $data['message'] = $ex->getMessage();
            return $data;
        }
    }

    public function paymentConfirmation($payment_id)
    {
        $data['success'] = false;
        $data['data'] = null;

        // Prepare the payload to confirm the payment
        $payload = [
            "action" => 'get_transaction',
            'receiver' => $this->username,
            'ref' => $payment_id,
            'trade_id' => 0
        ];

        $response = $this->curl_request($payload, $this->apiUrl."/api_v1", 'POST');

        if (isset($response->status_msg) && $response->status_msg === 'COMPLETED') {
            $data['success'] = true;
            $data['data']['amount'] = $response->amount;
            $data['data']['currency'] = $response->currency_code;
            $data['data']['payment_status'] = 'success';
            $data['data']['payment_method'] = ZITOPAY;
        } else {
            $data['data']['payment_status'] = 'unpaid';
            $data['data']['payment_method'] = ZITOPAY;
        }

        return $data;
    }

    private function curl_request($payload, $url, $method = 'GET')
    {
        try {
            $response = Http::asForm()->$method($url, $payload);

            if ($response->successful()) {
                return json_decode($response->body());
            } else {
                Log::error('ZitoPay API Error: ' . $response->body());
                throw new \Exception('API request failed');
            }
        } catch (\Exception $ex) {
            Log::error('ZitoPay Curl Request Error: ' . $ex->getMessage());
            throw $ex;
        }
    }
}
