<?php


namespace App\Services\Payment;

use App\Models\Gateway;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FlutterwaveService extends BasePaymentService
{
    private $orderId;
    private $public_key;
    private $client_secret;
    private $hash;

    public function __construct($method, $object)
    {
        parent::__construct($method, $object);
        if (isset($object['id'])) {
            $this->orderId = $object['id'];
        }
        $this->public_key = $this->gateway->key;
        $this->client_secret = $this->gateway->secret;
        $this->hash = $this->gateway->url;
    }

    public function makePayment($amount)
    {
        $this->setAmount($amount);

        $data['success'] = false;
        $data['redirect_url'] = '';
        $data['payment_id'] = '';
        $data['message'] = SOMETHING_WENT_WRONG;

        try {
            $this->verify_currency();

            $payment_id = sha1($this->orderId);
            $postData = [
                'payment_options' => 'card,banktransfer',
                'amount' => $this->amount,
                'email' => "",
                'tx_ref' => $payment_id,
                'currency' => $this->currency,
                'redirect_url' => $this->callbackUrl,
                'customer' => [
                    'email' => auth()->user()->email,
                    "name" => auth()->user()->name
                ],
                'meta' =>  [
                    'metaname' => 'order_id', 'metavalue' => $this->orderId,
                ]
            ];
            $payment = Http::withToken($this->client_secret)->post(
                'https://api.flutterwave.com/v3/payments',
                $postData
            )->json();
            if ($payment['status'] == 'success') {
                $data['redirect_url'] = $payment['data']['link'];
                $data['payment_id'] = $payment_id;
                $data['success'] = true;
                return $data;
            } else {
                throw new \Exception($payment['message']);
            }
        } catch (\Exception $ex) {
            $data['message'] = $ex->getMessage();
            return $data;
        }
    }

    public function paymentConfirmation($payment_id)
    {

        $data['success'] = false;
        $data['data'] = null;

        sleep(10);
        $payment =  Http::withToken($this->client_secret)
            ->get("https://api.flutterwave.com/v3" . "/transactions/?tx_ref=" . $payment_id)
            ->json();
        Log::info($payment);
        if ($payment['status'] == 'success' && isset($payment['data'][0]) && $payment['data'][0]['status'] == 'successful') {
            $data['success'] = true;
            $data['data']['amount'] = $payment['data'][0]['amount'];
            $data['data']['currency'] = $payment['data'][0]['currency'];
            $data['data']['payment_status'] =  'success';
            $data['data']['payment_method'] = FLUTTERWAVE;
        } else {
            $data['success'] = false;
            $data['data']['currency'] = $this->currency;
            $data['data']['payment_status'] =  'unpaid';
            $data['data']['payment_method'] = FLUTTERWAVE;
        }


        return $data;
    }

    public function verify_currency()
    {
        if (in_array($this->currency, $this->supported_currency_list(), true)) {
            return true;
        }
        return throw new \Exception($this->currency . __(' is not supported by ' . $this->gateway_name()));
    }

    public function supported_currency_list()
    {
        return ['BIF', 'CAD', 'CDF', 'CVE', 'EUR', 'GBP', 'GHS', 'GMD', 'GNF', 'KES', 'LRD', 'MWK', 'MZN', 'NGN', 'RWF', 'SLL', 'STD', 'TZS', 'UGX', 'USD', 'XAF', 'XOF', 'ZMK', 'ZMW', 'ZWD'];
    }


    public function gateway_name()
    {
        return 'flutterwave';
    }
}
