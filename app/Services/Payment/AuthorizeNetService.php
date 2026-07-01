<?php

namespace App\Services\Payment;

use Illuminate\Support\Facades\Log;
use net\authorize\api\contract\v1 as AnetAPI;
use net\authorize\api\controller as AnetController;

class AuthorizeNetService extends BasePaymentService
{
    public $merchantAuthentication;
    public $environment;
    public $baseUrl;

    public function __construct($method, $object)
    {
        parent::__construct($method, $object);

        // Set up the Merchant Authentication for Authorize.Net
        $this->merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
        $this->merchantAuthentication->setName($this->gateway->key); // API Login ID
        $this->merchantAuthentication->setTransactionKey($this->gateway->secret); // Transaction Key

        // Determine the environment based on the gateway mode
        if ($this->gateway->mode == GATEWAY_MODE_SANDBOX) {
            $this->environment = \net\authorize\api\constants\ANetEnvironment::SANDBOX;
            $this->baseUrl = 'https://test.authorize.net/payment/payment-form?token=';
        } else {
            $this->environment = \net\authorize\api\constants\ANetEnvironment::PRODUCTION;
            $this->baseUrl = 'https://accept.authorize.net/payment/payment-form?token=';
        }
    }

    public function makePayment($amount, $post_data = null)
    {
        $this->setAmount($amount);

        try {
            $token = $this->getHostedPaymentPageToken($this->amount, $this->callbackUrl);

            $data['success'] = true;
            $data['redirect_url'] = $this->baseUrl . $token;
            $data['payment_id'] = $token;

            Log::info('Authorize.Net Hosted Payment Token: ' . json_encode($data));
            return $data;

        } catch (\Exception $ex) {
            Log::error('Authorize.Net Error: ' . $ex->getMessage());
            $data['success'] = false;
            $data['message'] = $ex->getMessage();
            return $data;
        }
    }

    private function getHostedPaymentPageToken($amount, $returnUrl)
    {
        $refId = 'ref' . time();

        // Create a transaction request type object
        $transactionRequestType = new AnetAPI\TransactionRequestType();
        $transactionRequestType->setTransactionType("authCaptureTransaction");
        $transactionRequestType->setAmount($amount);

        // Create a SettingType for hosted payment button options
        $setting1 = new AnetAPI\SettingType();
        $setting1->setSettingName("hostedPaymentButtonOptions");
        $setting1->setSettingValue(json_encode(["text" => "Pay Now"]));

        // Create a SettingType for return options
        $setting2 = new AnetAPI\SettingType();
        $setting2->setSettingName("hostedPaymentReturnOptions");
        $setting2->setSettingValue(json_encode(["url" => $returnUrl, "showReceipt" => true]));

        // Create the request object and add settings
        $request = new AnetAPI\GetHostedPaymentPageRequest();
        $request->setMerchantAuthentication($this->merchantAuthentication);
        $request->setTransactionRequest($transactionRequestType);
        $request->addToHostedPaymentSettings($setting1);
        $request->addToHostedPaymentSettings($setting2);

        $controller = new AnetController\GetHostedPaymentPageController($request);
        $response = $controller->executeWithApiResponse($this->environment);

        if ($response != null && $response->getMessages()->getResultCode() == "Ok") {
            return $response->getToken();
        } else {
            $errorMessages = $response->getMessages()->getMessage();
            throw new \Exception("Error: " . $errorMessages[0]->getText());
        }
    }

    public function paymentConfirmation($payment_id)
    {
        $data['data'] = null;
        Log::info("------Authorize.Net Payment Confirmation----");
        Log::info($payment_id);

        try {
            // Create a transaction details request
            $request = new AnetAPI\GetTransactionDetailsRequest();
            $request->setMerchantAuthentication($this->merchantAuthentication);
            $request->setTransId($payment_id);

            // Execute the request
            $controller = new AnetController\GetTransactionDetailsController($request);
            $response = $controller->executeWithApiResponse($this->environment);

            Log::info(json_encode($response));

            if ($response != null && $response->getMessages()->getResultCode() == "Ok") {
                $transaction = $response->getTransaction();
                $data['success'] = $transaction->getTransactionStatus() == 'settledSuccessfully';
                $data['data']['amount'] = $transaction->getAuthAmount();
                $data['data']['currency'] = $transaction->getCurrencyCode();
                $data['data']['payment_status'] = $data['success'] ? 'success' : 'unpaid';
                $data['data']['payment_method'] = 'Authorize.Net';
            } else {
                $data['success'] = false;
                $data['message'] = "Failed to retrieve transaction details.";
                if ($response != null) {
                    $errorMessages = $response->getMessages()->getMessage();
                    if (!empty($errorMessages)) {
                        $data['message'] = $errorMessages[0]->getText();
                    }
                }
            }

        } catch (\Exception $ex) {
            Log::error('Authorize.Net Payment Confirmation Error: ' . $ex->getMessage());
            $data['success'] = false;
            $data['message'] = $ex->getMessage();
        }

        return $data;
    }

}
