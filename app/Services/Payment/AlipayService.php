<?php

namespace App\Services\Payment;

class AlipayService extends BasePaymentService
{
    public $alipayClient;
    protected $gatewayUrl = 'https://open-na-global.alipay.com';
    protected $appId;
    protected $privateKey;
    protected $alipayPublicKey;
    protected $signType = 'RSA2';

    public function __construct($method, $object)
    {
        parent::__construct($method, $object);
        $this->appId = $this->gateway->url;  // Assuming $this->gateway->key contains the Alipay app ID
        $this->privateKey = $this->gateway->secret;  // Assuming $this->gateway->private_key contains the private key
        $this->alipayPublicKey = $this->gateway->key;  // Assuming $this->gateway->public_key contains the Alipay public key
        $this->alipayClient = $this;  // Using the same class as the client
    }

    public function makePayment($amount)
    {
        $this->setAmount($amount);
        $data = [
            'success' => false,
            'redirect_url' => '',
            'payment_id' => '',
            'message' => SOMETHING_WENT_WRONG,
        ];

        try {
            $sale_id = uniqid();
            $description = 'Payment for order';
            $return_url = $this->callbackUrl;
            $notify_url = $this->callbackUrl;

            $paymentUrl = $this->createPayment($sale_id, $amount, $this->currency, $description, $return_url, $notify_url);

            if ($paymentUrl) {
                $data['payment_id'] = $sale_id;
                $data['success'] = true;
                $data['redirect_url'] = $paymentUrl;
            }

            return $data;
        } catch (\Exception $ex) {
            $data['message'] = $ex->getMessage();
        }

        return $data;
    }

    public function paymentConfirmation($payment_id)
    {
        $data = ['data' => null];

        try {
            $paymentData = $_POST;  // Assuming Alipay sends POST data for confirmation
            $isVerified = $this->verifyPayment($paymentData);

            if ($isVerified && $paymentData['out_trade_no'] === $payment_id) {
                $data['success'] = true;
                $data['data'] = [
                    'amount' => $paymentData['total_fee'],
                    'currency' => $this->currency,
                    'payment_status' => 'success',
                    'payment_method' => 'ALIPAY',
                ];
            } else {
                $data['success'] = false;
                $data['data'] = [
                    'amount' => $paymentData['total_fee'],
                    'currency' => $this->currency,
                    'payment_status' => 'unpaid',
                    'payment_method' => 'ALIPAY',
                ];
            }

            return $data;
        } catch (\Exception $ex) {
            $data['message'] = $ex->getMessage();
        }

        return $data;
    }

    protected function createPayment($sale_id, $amount, $currency, $description, $return_url, $notify_url)
    {
        $params = [
            'app_id' => $this->appId,
            'method' => 'alipay.trade.page.pay',
            'format' => 'JSON',
            'return_url' => $return_url,
            'notify_url' => $notify_url,
            'charset' => 'UTF-8',
            'sign_type' => $this->signType,
            'timestamp' => date('Y-m-d H:i:s'),
            'version' => '1.0',
            'biz_content' => json_encode([
                'out_trade_no' => $sale_id,
                'product_code' => 'FAST_INSTANT_TRADE_PAY',
                'total_amount' => $amount,
                'subject' => $description,
            ]),
        ];

        $params['sign'] = $this->generateSign($params);

        $query = http_build_query($params);
        return $this->gatewayUrl . '?' . $query;
    }

    protected function verifyPayment($paymentData)
    {
        $sign = $paymentData['sign'];
        unset($paymentData['sign']);
        unset($paymentData['sign_type']);

        $content = urldecode(http_build_query($paymentData));
        $publicKey = "-----BEGIN PUBLIC KEY-----\n" . wordwrap($this->alipayPublicKey, 64, "\n", true) . "\n-----END PUBLIC KEY-----";

        return openssl_verify($content, base64_decode($sign), $publicKey, OPENSSL_ALGO_SHA256) === 1;
    }

    protected function generateSign($params)
    {
        ksort($params);
        $stringToSign = urldecode(http_build_query($params));
        $privateKey = "-----BEGIN PRIVATE KEY-----\n" . wordwrap($this->privateKey, 64, "\n", true) . "\n-----END PRIVATE KEY-----";

        openssl_sign($stringToSign, $sign, $privateKey, OPENSSL_ALGO_SHA256);
        return base64_encode($sign);
    }
}
