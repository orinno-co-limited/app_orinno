<?php

namespace App\Services\Payment;

use Razorpay\Api\Api;

class RazorpayService extends BasePaymentService
{
    protected $gatewayKeyId;
    protected $gatewayKeySecret;
    protected $api;

    public function __construct($method, $object)
    {
        parent::__construct($method, $object);

        $this->gatewayKeyId = $this->gateway->key;
        $this->gatewayKeySecret = $this->gateway->secret;
        $this->api = new Api($this->gatewayKeyId, $this->gatewayKeySecret);
    }

    public function makePayment($amount)
    {
        $this->setAmount($amount);
        $data['success'] = false;
        $data['redirect_url'] = '';
        $data['payment_id'] = '';
        $data['message'] = SOMETHING_WENT_WRONG;
        try {
            $payment = $this->api->paymentLink->create(
                array(
                    'amount' => $this->amount * 100,
                    'currency' => $this->currency,
                    'accept_partial' => true,
                    'callback_url' => $this->callbackUrl,
                    'callback_method' => 'get'
                )
            );

            if ($payment->status = 'status') {
                $data['redirect_url'] = $payment->short_url;
                $data['payment_id'] = $payment->id;
                $data['success'] = true;
            }
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
        $response =  $this->api->paymentLink->fetch($payment_id);

        if ($response['status'] == 'paid') {
            $data['success'] = true;
            $data['data']['amount'] = $response['amount'] / 100;
            $data['data']['currency'] = $response['currency'];
            $data['data']['payment_status'] =  'success';
            $data['data']['payment_method'] = RAZORPAY;
        } else {
            $data['success'] = false;
            $data['data']['payment_status'] =  'unpaid';
            $data['data']['payment_method'] = RAZORPAY;
        }
        return $data;
    }
}
