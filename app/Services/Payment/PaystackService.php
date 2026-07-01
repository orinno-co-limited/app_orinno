<?php


namespace App\Services\Payment;

use Illuminate\Support\Facades\Log;

class PaystackService extends BasePaymentService
{
    private $paymentApiUrl = 'https://api.paystack.co/transaction/initialize';
    private $transactionVerifyApiUrl = 'https://api.paystack.co/transaction/verify/';
    private $apiSecret;
    private $merchantEmail;
    private $id;

    public function __construct($method, $object)
    {
        parent::__construct($method, $object);
        if (isset($object['id'])) {
            $this->id = $object['id'];
        }

        $this->apiSecret = $this->gateway->key;
        $this->merchantEmail = env('MERCHANT_EMAIL', 'marchant@gmail.com');
    }

    public function makePayment($amount)
    {
        $this->setAmount($amount);
        $payload = array(
            "callback_url" => $this->callbackUrl,
            "amount" => $this->amount * 100,
            "email" => $this->merchantEmail,
            "currency" => $this->currency,
            "orderID" => $this->id,
        );
        $response = $this->curl_request($payload, $this->paymentApiUrl);

        $data['success'] = false;
        $data['redirect_url'] = '';
        $data['payment_id'] = '';
        $data['message'] = SOMETHING_WENT_WRONG;
        try {
            if ($response->status) {
                $data['redirect_url'] = $response->data->authorization_url;
                $data['payment_id'] = $response->data->reference;
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
        $url = $this->transactionVerifyApiUrl . $payment_id;
        $payment = $this->curl_request([], $url, 'GET');
        if ($payment->status && $payment->data->status == 'success') {
            $data['success'] = true;
            $data['data']['amount'] = $payment->data->amount;
            $data['data']['currency'] = $this->currency;
            $data['data']['payment_status'] =  'success';
            $data['data']['payment_method'] = PAYSTACK;
            // Store in your local database that the transaction was paid successfully
        } else {
            $data['success'] = false;
            $data['data']['amount'] = $payment->data->amount;
            $data['data']['currency'] = $this->currency;
            $data['data']['payment_status'] =  'unpaid';
            $data['data']['payment_method'] = PAYSTACK;
        }
        return $data;
    }

    public  function curl_request($payload, $url, $method = 'POST')
    {


        $fields_string = http_build_query($payload);

        //open connection
        $ch = curl_init();

        //set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);
        if ($method == 'POST') {
            curl_setopt($ch, CURLOPT_POST, true);
        } else {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        }
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Authorization: Bearer " . $this->apiSecret,
            "Cache-Control: no-cache",
        ));

        //So that curl_exec returns the contents of the cURL; rather than echoing it
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        //execute post
        $result = curl_exec($ch);
        return json_decode($result);
    }
}
