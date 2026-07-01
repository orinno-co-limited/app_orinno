<?php

namespace App\Services\Payment;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class ToyyibPayService extends BasePaymentService
{
    private $toyyibUrl;
    private $userSecretKey;
    private $categoryCode;

    public function __construct($method, $object)
    {
        parent::__construct($method, $object);

        // You can move these to .env and access via config()
        $this->toyyibUrl = $this->gateway->mode == GATEWAY_MODE_LIVE ?  'https://toyyibpay.com' : 'https://dev.toyyibpay.com';
        $this->categoryCode = $this->gateway->secret;
        $this->userSecretKey = $this->gateway->key;

        if (isset($object['id'])) {
            $this->orderId = $object['id'];
        }
    }

    public function makePayment($amount)
    {
        $this->setAmount($amount);

        $transaction_id = sha1($this->orderId);

        // Prepare payload for creating the bill
        $payload = [
            'userSecretKey' => $this->userSecretKey,
            'categoryCode' => $this->categoryCode,
            'billName' => 'Product Purchase',
            'billDescription' => 'Description of the product',
            'billAmount' => $this->amount*100,
            'billReturnUrl' => $this->callbackUrl,
            'billCallbackUrl' => $this->callbackUrl,
            'billExternalReferenceNo' => $transaction_id,
            'billTo' => Auth::user()->name ?? 'User',
            'billEmail' => Auth::user()->email,
            'billPhone' => Auth::user()->mobile ?? '000000',
            'billPaymentChannel' => 0, // Set to 0 to allow all payment channels
            'billChargeToCustomer' => 1, // 1 to charge customer
            'billPriceSetting' => 1, // Set to 0 for a fixed price
            'billPayorInfo' => 0
        ];

        try {
            // Step 1: Create a bill using the ToyyibPay API
            $response = Http::asForm()->post("{$this->toyyibUrl}/index.php/api/createBill", $payload);

            if ($response->successful()) {
                $responseBody = $response->getBody()->getContents();
                $responseData = json_decode($responseBody, true);
                if(isset($responseData[0]['BillCode'])){
                    $billCode = $response[0]['BillCode'];
                    // Step 2: Redirect user to the payment URL
                    $data['success'] = true;
                    $data['redirect_url'] = "{$this->toyyibUrl}/{$billCode}";
                    $data['payment_id'] = $billCode;
                }else{
                    $data['success'] = false;
                    $data['message'] = 'Failed to create payment bill.';
                }
            } else {
                $data['success'] = false;
                $data['message'] = 'Failed to create payment bill.';
            }
        } catch (\Exception $e) {
            $data['success'] = false;
            $data['redirect_url'] = '';
            $data['payment_id'] = '';
            $data['message'] = $e->getMessage();
        }

        return $data;
    }

    /**
     * @param $payment_id
     * @param null $payer_id
     * @return array
     */
    public function paymentConfirmation($payment_id, $payer_id = null)
    {
        $data = [
            'success' => false,
            'message' => 'Payment verification failed',
        ];

        // Prepare the payload with the necessary parameters
        $payload = [
            'userSecretKey' => $this->userSecretKey,
            'billCode' => $payment_id, // Use the billCode to retrieve the transaction status
        ];

        try {
            // Step 1: Make an API call to get bill transactions
            $response = Http::asForm()->post("{$this->toyyibUrl}/index.php/api/getBillTransactions", $payload);

            // Step 2: Check if the response is successful and decode the body
            if ($response->successful()) {
                $responseBody = $response->getBody()->getContents();
                $responseData = json_decode($responseBody, true);

                // Step 3: Validate the response structure
                if (isset($responseData[0]['billpaymentStatus'])) {
                    $paymentStatus = $responseData[0]['billpaymentStatus'];

                    if ($paymentStatus == 1) { // 1 indicates a successful payment
                        // Payment was successful
                        $data['success'] = true;
                        $data['message'] = 'Payment approved';
                        $data['data'] = [
                            'amount' => $responseData[0]['billpaymentAmount'], // Amount is in MYR
                            'currency' => 'MYR', // Assuming ToyyibPay processes in MYR
                            'payment_status' => 'success',
                            'payment_method' => 'ToyyibPay',
                            'payment_date' => $responseData[0]['billPaymentDate'],
                            'transaction_reference' => $responseData[0]['billExternalReferenceNo'],
                            'invoice_no' => $responseData[0]['billpaymentInvoiceNo'],
                            'settlement_status' => $responseData[0]['billpaymentSettlement'], // e.g., Pending Settlement
                        ];
                    } else {
                        // Payment failed
                        $data['data'] = [
                            'payment_status' => 'failed',
                            'transaction_reference' => $responseData[0]['billExternalReferenceNo'],
                        ];
                    }
                } else {
                    // No valid payment status found
                    $data['message'] = 'No transaction found for the provided BillCode.';
                }
            } else {
                // Failed response
                $data['message'] = 'Failed to retrieve transaction details.';
            }
        } catch (\Exception $e) {
            // Exception handling
            $data['message'] = $e->getMessage();
        }

        return $data;
    }
}
