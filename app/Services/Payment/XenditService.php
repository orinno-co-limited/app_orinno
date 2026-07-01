<?php
namespace App\Services\Payment;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class XenditService extends BasePaymentService
{
    private $paymentApiUrl = 'https://api.xendit.co/v2/invoices';
    private $transactionVerifyApiUrl = 'https://api.xendit.co/v2/invoices/';
    private $apiSecret;
    private $id;

    public function __construct($method, $object)
    {
        parent::__construct($method, $object);
        if (isset($object['id'])) {
            $this->id = $object['id'];
        }

        $this->apiSecret = $this->gateway->key;
    }

    public function makePayment($amount)
    {
        $this->setAmount($amount);
        $order_id = uniqid();
        $payload = array(
            "external_id" => $order_id,
            "amount" => $this->amount,
            "payer_email" => Auth::user()->email,
            "description" => "Payment for Order #{$order_id}",
            "currency" => $this->currency,
            "callback_url" => $this->callbackUrl,
            "success_redirect_url" => $this->callbackUrl, // URL to redirect after successful payment
            "failure_redirect_url" => $this->callbackUrl,
        );


        $response = $this->curl_request($payload, $this->paymentApiUrl);
        $data['success'] = false;
        $data['redirect_url'] = '';
        $data['payment_id'] = '';
        $data['message'] = 'Something went wrong';
        try {
            if (!empty($response->id)) {
                $data['redirect_url'] = $response->invoice_url;
                $data['payment_id'] = $response->id;
                $data['success'] = true;
            }else{
                $data['message'] = $response->message;
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
        $url = $this->transactionVerifyApiUrl . $payment_id;
        $payment = $this->curl_request([], $url, 'GET');
        if (!empty($payment->id) && $payment->status == 'PAID') {
            $data['success'] = true;
            $data['data']['amount'] = $payment->amount;
            $data['data']['currency'] = $this->currency;
            $data['data']['payment_status'] = 'success';
            $data['data']['payment_method'] = 'Xendit';
            // Store in your local database that the transaction was paid successfully
        } else {
            $data['success'] = false;
            $data['data']['amount'] = $payment->amount;
            $data['data']['currency'] = $this->currency;
            $data['data']['payment_status'] = 'unpaid';
            $data['data']['payment_method'] = 'Xendit';
        }

        return $data;
    }

    public function curl_request($payload, $url, $method = 'POST')
    {
        $fields_string = json_encode($payload);

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        if ($method == 'POST') {
            curl_setopt($ch, CURLOPT_POST, true);
        } else {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        }
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Authorization: Basic " . base64_encode($this->apiSecret . ":"),
            "Content-Type: application/json",
            "Cache-Control: no-cache",
        ));

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $result = curl_exec($ch);
        return json_decode($result);
    }
}
