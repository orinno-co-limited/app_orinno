<?php

namespace App\Services\Payment;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class BinancePaymentService extends BasePaymentService
{
    protected $binanceApi;
    protected $binanceSecret;
    protected $url;

    public function __construct($method, $object)
    {
        parent::__construct($method, $object);

        // Retrieve the API and Secret from the gateway configuration
        $this->binanceApi = $this->gateway->key; // Assuming key stores the Binance API key
        $this->binanceSecret = $this->gateway->secret; // Assuming secret stores the Binance secret

        $this->url = 'https://bpay.binanceapi.com/binancepay/openapi/v3/order';
    }

    public function makePayment($amount, $post_data = null)
    {
        $this->setAmount($amount);

        $nonce = $this->generateNonce();
        $timestamp = round(microtime(true) * 1000);
        $uniqid = uniqid() . rand(10000, 99999);

        $data = [
            "env" => [
                "terminalType" => "WEB"
            ],
            "merchantTradeNo" => $uniqid,
            "orderAmount" => $this->amount,
            "currency" => $this->currency ?? 'USDT',
            "description" => "Payment to ".getOption('app_name') ?? 'Zaialumni',
            "goodsDetails" => [
                [
                    "goodsType" => "01",
                    "goodsCategory" => "D000",
                    "referenceGoodsId" => $uniqid,
                    "goodsName" => "Ice Cream",
                    "goodsDetail" => "Greentea ice cream cone"
                ]
            ],
            "returnUrl" => $this->callbackUrl, // Include the callback URL here
            "cancelUrl" => $this->callbackUrl // Include the callback URL here
        ];

        $jsonRequest = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        $payload = $timestamp . "\n" . $nonce . "\n" . $jsonRequest . "\n";
        $signature = strtoupper(hash_hmac('SHA512', $payload, $this->binanceSecret));

        $response = Http::withHeaders([
            "Content-Type" => "application/json",
            "BinancePay-Timestamp" => $timestamp,
            "BinancePay-Nonce" => $nonce,
            "BinancePay-Certificate-SN" => $this->binanceApi,
            "BinancePay-Signature" => $signature,
        ])->withBody($jsonRequest, 'application/json')  // Sending JSON request as the body
            ->post($this->url)
            ->json();

        Log::info('Binance Payment Response:', $response);

        $data['success'] = false;
        $data['redirect_url'] = '';
        $data['payment_id'] = '';
        $data['message'] = __('Something went wrong');

        if ($response['status'] == "SUCCESS") {
            $data['success'] = true;
            $data['redirect_url'] = $response['data']['checkoutUrl'] ?? '';
            $data['payment_id'] = $uniqid;
        }

        return $data;
    }

    public function paymentConfirmation($payment_id, $payer_id = null)
    {
        $data['success'] = false;
        $data['data'] = null;

        // Signature verification successful, now query Binance for payment status
        $queryPayload = [
            "merchantTradeNo" => $payment_id
        ];

        $timestamp = round(microtime(true) * 1000);
        $nonce = $this->generateNonce();

        $jsonRequest = json_encode($queryPayload);
        $payloadToSign = $timestamp . "\n" . $nonce . "\n" . $jsonRequest . "\n";
        $querySignature = strtoupper(hash_hmac('SHA512', $payloadToSign, $this->binanceSecret));

        $response = Http::withHeaders([
            "Content-Type" => "application/json",
            "BinancePay-Timestamp" => $timestamp,
            "BinancePay-Nonce" => $nonce,
            "BinancePay-Certificate-SN" => $this->binanceApi,
            "BinancePay-Signature" => $querySignature,
        ])->withBody($jsonRequest, 'application/json')  // Sending JSON request as the body
            ->post('https://bpay.binanceapi.com/binancepay/openapi/v2/order/query')
            ->json();

        if ($response['status'] == "SUCCESS" && $response['data']['status'] == "PAID") {
            $data['success'] = true;
            $data['data']['payment_status'] = 'success';
            $data['data']['payment_method'] = 'BINANCE';
            $data['data']['amount'] = $response['data']['totalFee'];
            $data['data']['currency'] = $response['data']['currency'];
            $data['data']['transaction_id'] = $response['data']['transactionId'];
            $data['data']['merchant_trade_no'] = $response['data']['merchantTradeNo'];
        } else {
            $data['message'] = $response['errorMessage'] ?? 'Payment verification failed';
        }

        return $data;
    }

    protected function generateNonce()
    {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $nonce = '';
        for ($i = 1; $i <= 32; $i++) {
            $pos = mt_rand(0, strlen($chars) - 1);
            $char = $chars[$pos];
            $nonce .= $char;
        }
        return $nonce;
    }
}
