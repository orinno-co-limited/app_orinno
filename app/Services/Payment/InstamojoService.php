<?php


namespace App\Services\Payment;

use App\Models\Gateway;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class InstamojoService extends BasePaymentService
{
    private $apiUrl;
    private $apiKey;
    private $apiToken;

    public function __construct($method, $object)
    {
        parent::__construct($method, $object);
        $this->apiUrl = $this->gateway->mode == GATEWAY_MODE_LIVE ? 'https://www.instamojo.com/api/1.1/payment-requests/' : 'https://test.instamojo.com/api/1.1/payment-requests/';
        $this->apiKey = $this->gateway->key;
        $this->apiToken = $this->gateway->secret;
    }

    public function makePayment($amount)
    {
        $this->setAmount($amount);

        $payload = array(
            'purpose' => 'Course Purchase',
            'amount' => $this->amount,
            'buyer_name' => Auth::user()->name,
            'redirect_url' => $this->callbackUrl,
            'send_email' => true,
            'send_sms' => false,
            'email' => Auth::user()->email,
            'allow_repeated_payments' => false
        );

        $response = $this->curl_request($payload, $this->apiUrl, "POST");

        Log::info('<<<<response>>>>');
        Log::info(json_encode($response));

        $data['success'] = false;
        $data['redirect_url'] = '';
        $data['payment_id'] = '';
        $data['message'] = SOMETHING_WENT_WRONG;
        try {
            if ($response->success) {
                $data['redirect_url'] = $response->payment_request->longurl;
                $data['payment_id'] = $response->payment_request->id;
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
        $url = $this->apiUrl . $payment_id;
        $payment = $this->curl_request([], $url);
        if ($payment->payment_request->status == 'Completed') {
            $data['success'] = true;
            $data['data']['amount'] = $payment->payment_request->amount;
            $data['data']['currency'] = $this->currency;
            $data['data']['payment_status'] =  'success';
            $data['data']['payment_method'] = INSTAMOJO;
            // Store in your local database that the transaction was paid successfully
        } else {
            $data['success'] = false;
            $data['data']['amount'] = $payment->payment_request->amount;
            $data['data']['currency'] = $this->currency;
            $data['data']['payment_status'] =  'unpaid';
            $data['data']['payment_method'] = INSTAMOJO;
        }
        return $data;
    }

    public  function curl_request($payload, $url, $method = 'GET')
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
                "X-Api-Key:" . $this->apiKey,
                "X-Auth-Token:" . $this->apiToken
            )
        );
        if ($method == 'POST') {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($payload));
        }
        $response = curl_exec($ch);
        curl_close($ch);
        $response = json_decode($response);
        return $response;
    }
}
