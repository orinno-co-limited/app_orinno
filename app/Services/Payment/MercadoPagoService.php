<?php


namespace App\Services\Payment;

use App\Models\Gateway;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MercadoPagoService extends BasePaymentService
{
    private $orderId;
    private $client_id;
    private $client_secret;
    private $test_mode;

    public function __construct($method, $object)
    {
        parent::__construct($method, $object);

        $this->orderId = $object['id'] ?? null;
        $this->client_id = $this->gateway->key;
        $this->client_secret = $this->gateway->secret;
        $this->test_mode = $this->gateway->mode === GATEWAY_MODE_SANDBOX;
    }

    private function getAccessToken()
    {
        $response = Http::asForm()->post('https://api.mercadopago.com/oauth/token', [
            'grant_type' => 'client_credentials',
            'client_id' => $this->client_id,
            'client_secret' => $this->client_secret,
        ]);

        if ($response->successful()) {
            return $response->json('access_token');
        }

        throw new \Exception('Unable to retrieve access token: ' . $response->body());
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
            $this->verify_currency();
            $accessToken = $this->getAccessToken();

            $response = Http::withToken($accessToken)->post('https://api.mercadopago.com/checkout/preferences', [
                'items' => [
                    [
                        'id' => $this->orderId,
                        'title' => "Order #{$this->orderId}",
                        'quantity' => 1,
                        'unit_price' => $this->amount,
                    ]
                ],
                'back_urls' => [
                    'success' => $this->callbackUrl,
                    'failure' => $this->callbackUrl,
                    'pending' => $this->callbackUrl,
                ],
                'auto_return' => 'approved',
                'metadata' => [
                    'order_id' => $this->orderId,
                ],
            ]);

            if ($response->successful()) {
                $responseBody = $response->json();
                $data['success'] = true;
                $data['redirect_url'] = $responseBody['init_point'];
                $data['payment_id'] = $responseBody['id'];
            } else {
                $data['message'] = $response->body();
            }
        } catch (\Exception $ex) {
            Log::error('MercadoPago makePayment error: ' . $ex->getMessage());
            $data['message'] = $ex->getMessage();
        }

        return $data;
    }

    public function paymentConfirmation($payment_id)
    {
        $data = [
            'success' => false,
            'data' => null,
        ];

        try {
            $accessToken = $this->getAccessToken();

            $response = Http::withToken($accessToken)->get("https://api.mercadopago.com/v1/payments/{$payment_id}");
            if ($response->successful()) {
                $payment = $response->json();

                if ($payment['status'] === 'approved') {
                    $data['success'] = true;
                    $data['data'] = [
                        'amount' => $payment['transaction_amount'],
                        'currency' => $this->currency,
                        'payment_status' => 'success',
                        'payment_method' => MERCADOPAGO,
                    ];
                } else {
                    $data['data'] = [
                        'currency' => $this->currency,
                        'payment_status' => 'unpaid',
                        'payment_method' => MERCADOPAGO,
                    ];
                }
            } else {
                $data['message'] = $response->body();
            }
        } catch (\Exception $ex) {
            Log::error('MercadoPago paymentConfirmation error: ' . $ex->getMessage());
            $data['message'] = $ex->getMessage();
        }

        return $data;
    }

    public function verify_currency()
    {
        if (!in_array($this->currency, $this->supported_currency_list(), true)) {
            throw new \Exception($this->currency . __(' is not supported by ' . $this->gateway_name()));
        }

        return true;
    }

    public function supported_currency_list()
    {
        return ['BRL', 'ARS', 'MXN', 'USD', 'COP', 'CLP', 'UYU', 'PEN', 'VEF', 'PYG'];
    }

    public function gateway_name()
    {
        return 'mercadopago';
    }
}
