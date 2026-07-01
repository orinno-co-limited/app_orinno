<?php


namespace App\Services\Payment;


use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Mollie\Api\MollieApiClient;
use App\Models\Gateway;

class MollieService extends BasePaymentService
{
    public $apiClient;

    public function __construct($method, $object)
    {
        parent::__construct($method, $object);
        $this->apiClient = new MollieApiClient();
        $this->apiClient->setApiKey($this->gateway->key);
    }

    public function makePayment($amount)
    {
        $this->setAmount($amount);
        $data['success'] = false;
        $data['redirect_url'] = '';
        $data['payment_id'] = '';
        $data['message'] = SOMETHING_WENT_WRONG;
        try {
            $param = [
                "amount" => [
                    "currency" => $this->currency,
                    "value" => str(number_format($this->amount, 2, '.', ''))
                ],
                "description" => Auth::user()->name,
                "redirectUrl" => $this->callbackUrl,
            ];
            $payment = $this->apiClient->payments->create($param);
            Log::info("payment");
            Log::info(json_encode($payment));

            if ($payment->status) {
                $data['redirect_url'] = $payment->getCheckoutUrl();
                $data['payment_id'] = $payment->id;
                $data['success'] = true;
            }
            Log::info(json_encode($data));
            return $data;
        } catch (\Exception $ex) {
            $data['message'] = $ex->getMessage();
            return $data;
        }
    }

    public function paymentConfirmation($payment_id)
    {
        $data['data'] = null;
        $payment = $this->apiClient->payments->get($payment_id);
        if ($payment->isPaid()) {
            $data['success'] = true;
            $data['data']['amount'] = $payment->amount->value;
            $data['data']['currency'] = $payment->amount->currency;
            $data['data']['payment_status'] =  'success';
            $data['data']['payment_method'] = MOLLIE;
            // Store in your local database that the transaction was paid successfully
        } elseif ($payment->isCanceled() || $payment->isExpired()) {
            $data['success'] = false;
            $data['data']['amount'] = $payment->amount->value;
            $data['data']['currency'] = $payment->amount->currency;
            $data['data']['payment_status'] =  'unpaid';
            $data['data']['payment_method'] = MOLLIE;
        } else {
            $data['success'] = false;
            $data['data']['amount'] = $payment->amount->value;
            $data['data']['currency'] = $payment->amount->currency;
            $data['data']['payment_status'] =  'unpaid';
            $data['data']['payment_method'] = MOLLIE;
        }
        return $data;
    }
}
