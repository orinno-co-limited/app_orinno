<?php

namespace App\Services\Payment;

use paytm\paytmchecksum\PaytmChecksum;

class PaytmService extends BasePaymentService
{
    private $url;
    private $merchantId;
    private $merchantKey;
    private $merchantWebsite;
    private $industryType;
    private $channel;
    private $orderId;

    public function __construct($method, $object)
    {
        parent::__construct($method, $object);

        // Set credentials from gateway properties
        $this->merchantId = $this->gateway->secret;
        $this->merchantKey = $this->gateway->key;
        $this->industryType = $this->gateway->url;

        // Additional required configurations
        $this->merchantWebsite = $this->gateway->mode == GATEWAY_MODE_LIVE ? 'DEFAULT' : 'WEBSTAGING';
        $this->channel = 'WEB';

        $this->orderId = uniqid();

        // Set the appropriate Paytm URL based on the mode (live or staging)
        $this->url = $this->gateway->mode == GATEWAY_MODE_LIVE
            ? "https://securegw.paytm.in" // Live URL
            : "https://securegw-stage.paytm.in"; // Staging URL
    }

    /**
     * Initiate a Paytm payment
     * @param float $amount
     * @return array
     */
    public function makePayment($amount)
    {
        $this->setAmount($amount);

        $paytmParams = array();

        // Body parameters for the request
        $paytmParams["body"] = array(
            "requestType"   => "Payment",
            "mid"           => $this->merchantId,
            "websiteName"   => $this->merchantWebsite,
            "orderId"       => $this->orderId,
            "callbackUrl"   => $this->callbackUrl,
            "txnAmount"     => array(
                "value"     => $this->amount,
                "currency"  => $this->currency
            ),
            "userInfo"      => array(
                "custId"    => uniqid()  // Random unique customer ID
            ),
        );

        // Generate checksum
        $checksum = PaytmChecksum::generateSignature(json_encode($paytmParams["body"], JSON_UNESCAPED_SLASHES), $this->merchantKey);

        // Head parameters containing the generated signature
        $paytmParams["head"] = array(
            "signature"    => $checksum
        );

        // Convert params to JSON format for the cURL request
        $post_data = json_encode($paytmParams, JSON_UNESCAPED_SLASHES);

        $orderUrl = $this->url."/theia/api/v1/initiateTransaction?mid={$this->merchantId}&orderId={$this->orderId}";
        // cURL request to Paytm
        $ch = curl_init($orderUrl);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
        $response = curl_exec($ch);

        // Handle errors
        if (curl_errno($ch)) {
            $error_message = curl_error($ch);
            curl_close($ch);
            return [
                'success' => false,
                'message' => $error_message
            ];
        }

        curl_close($ch);

        $result = json_decode($response, true);

        // Check if the response contains a success status
        if (isset($result['body']['resultInfo']['resultStatus']) && $result['body']['resultInfo']['resultStatus'] == 'S') {
            // Create the form using the txnToken received from Paytm
            $txnToken = $result['body']['txnToken'];
            $formHtml = $this->generatePaytmPaymentForm($txnToken);

            // Store the form in session so the view can auto-submit
            session()->flash('payment_form_html', $formHtml);

            // Get the previous URL
            $previousUrl = url()->previous();

            $data['redirect_url'] = $previousUrl;
            $data['payment_id'] = $this->orderId;
            $data['success'] = true;

            return $data;
        } else {
            return [
                'success' => false,
                'message' => 'Payment initiation failed',
            ];
        }
    }

    private function generatePaytmPaymentForm($txnToken)
    {
        $mid = $this->merchantId;
        $orderId = $this->orderId;

        // Build the HTML form
        $form = <<<HTML
    <form method="post" action="{$this->url}/theia/api/v1/showPaymentPage?mid={$mid}&orderId={$orderId}" name="paytm">
         <input type="hidden" name="mid" value="{$mid}">
         <input type="hidden" name="orderId" value="{$orderId}">
         <input type="hidden" name="txnToken" value="{$txnToken}">
    </form>
HTML;

        return $form;
    }


    /**
     * Validate the Paytm payment confirmation from callback
     * @param string $orderId
     * @return array
     */
    public function paymentConfirmation($orderId)
    {
        $paytmParams = array();

        // Body parameters
        $paytmParams["body"] = array(
            "mid" => $this->merchantId,
            "orderId" => $orderId,
        );

        // Generate checksum
        $checksum = PaytmChecksum::generateSignature(json_encode($paytmParams["body"], JSON_UNESCAPED_SLASHES), $this->merchantKey);

        // Head parameters containing the generated checksum
        $paytmParams["head"] = array(
            "signature" => $checksum
        );

        // Convert the request data to JSON format
        $post_data = json_encode($paytmParams, JSON_UNESCAPED_SLASHES);

        // cURL request to Paytm to check the payment status
        $ch = curl_init($this->url."/v3/order/status");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            $error_message = curl_error($ch);
            curl_close($ch);
            return [
                'success' => false,
                'message' => $error_message
            ];
        }

        curl_close($ch);

        // Decode the response from Paytm
        $result = json_decode($response, true);

        $data['success'] = false;
        $data['data'] = null;

        // Check if the result contains a valid status
        if (isset($result['body']['resultInfo']['resultStatus']) && $result['body']['resultInfo']['resultStatus'] == 'TXN_SUCCESS') {
            $data['success'] = true;
            $data['data'] = [
                'payment_status' => 'success',
                'payment_method' => PAYTM
            ];
        }else{
            $data['message'] = 'Payment not approved';
        }

        return $data;
    }
}
