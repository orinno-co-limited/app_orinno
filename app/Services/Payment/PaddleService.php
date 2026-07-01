<?php

namespace App\Services\Payment;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class PaddleService extends BasePaymentService
{
    public  $paddle;
    public  $baseUrl;
    public  $payment_id;
    private $orderId;


    public function __construct($method, $object)
    {
        parent::__construct($method, $object);
        if (isset($object['id'])) {
            $this->orderId = $object['id'];
        }
    }

    public function makePayment($amount)
    {
        $this->payment_id = sha1($this->orderId);
        $this->setAmount($amount);
        $data['success'] = false;
        $data['redirect_url'] = '';
        $data['payment_id'] = '';
        $data['message'] = SOMETHING_WENT_WRONG;

        try {
            $response = $this->generatePayLink($this->amount);
            Log::info(json_encode($response));
            if ($response['success']) {
                $data['payment_id'] = $this->payment_id;
                $data['success'] = true;
                $data['redirect_url'] = $response['response']['url'];
            }
            return $data;
        } catch (\Exception $ex) {
            $data['message'] = $ex->getMessage();
        }
        return $data;
    }

    public function paymentConfirmation($payment_id)
    {
        $data['data'] = null;
        Log::info("------payment----");
        Log::info($payment_id);
        $payment = $this->stripClient->checkout->sessions->retrieve($payment_id, []);
        Log::info(json_encode($payment));
        if ($payment->payment_status == 'paid') {
            $data['success'] = true;
            $data['data']['amount'] = $payment->amount_total / 100;
            $data['data']['currency'] = $payment->currency;
            $data['data']['payment_status'] =  'success';
            $data['data']['payment_method'] = STRIPE;
        } else {
            $data['success'] = false;
            $data['data']['amount'] = $payment->amount_total / 100;
            $data['data']['currency'] = $payment->currency;
            $data['data']['payment_status'] =  'unpaid';
            $data['data']['payment_method'] = STRIPE;
        }

        return $data;
    }

    public function generatePayLink($price)
    {
        if ($this->gateway->mode === GATEWAY_MODE_SANDBOX) {
            $this->baseUrl = 'https://sandbox-api.paddle.com/api/2.0/';
        } else {
            $this->baseUrl = 'https://vendors.paddle.com/api/2.0/';
        }
        $options = [
            'vendor_id' => $this->gateway->url,
            'vendor_auth_code' => $this->gateway->key,
            'prices' => ['USD:'.$price],
            'custom_message' => $this->payment_id,
            'return_url' => $this->callbackUrl,
            'customer_email' => Auth::user()->email,
            'webhook_url' => $this->callbackUrl,
        ];
        $response = Http::asForm()->post($this->baseUrl.'product/generate_pay_link', $options);
        return $response->json();
    }
}
