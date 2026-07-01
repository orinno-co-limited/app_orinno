<?php

namespace App\Services\Payment;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Vrajroham\LaravelBitpay\LaravelBitpay;

class BitPayService extends BasePaymentService
{
    private $merchantToken;
    private $orderId;

    public function __construct($method, $object)
    {
        parent::__construct($method, $object);

        if (isset($object['id'])) {
            $this->orderId = $object['id'];
        }

        // Fetch gateway configuration details similar to PayHere
        $this->merchantToken = $this->gateway->key;
        config(['laravel-bitpay.network' =>  $this->gateway->mode == GATEWAY_MODE_LIVE ? 'livenet' : 'testnet']);
        config(['laravel-bitpay.merchant_token' => $this->gateway->key]);
    }

    public function makePayment($amount)
    {
        $this->setAmount($amount); // Set amount from base service

        // Generate unique order ID similar to PayHere
        $paymentId = sha1($this->orderId);
        $userId = auth()->id();

        // Prepare timestamp for signature
        $timestamp = time();

        // Generate signature using merchant token, user ID, order ID, and timestamp
        $signature = hash_hmac('sha256', $userId . '|' . $paymentId . '|' . $timestamp, $this->merchantToken);

        // Encrypt user ID and timestamp
        $encryptedData = encrypt($userId . '|' . $timestamp);

        // Update callback URL with encrypted data and signature
        $callbackUrlWithData = $this->callbackUrl . '&data=' . urlencode($encryptedData) . '&signature=' . $signature;

        try {
            // Prepare the invoice for BitPay
            $invoice = LaravelBitpay::Invoice();
            $invoice->setItemDesc(getOption('site_name', 'App'));
            $invoice->setPrice($this->amount);
            $invoice->setOrderId($paymentId);
            $invoice->setCurrency($this->currency);
            $invoice->setRedirectURL($callbackUrlWithData); // Set the callback URL with encrypted data and signature

            // Add buyer details
            $buyer = LaravelBitpay::Buyer();
            $buyer->setName(Auth::user()->name);
            $buyer->setEmail(Auth::user()->email);
            $buyer->setAddress1(Auth::user()->address ?? 'No Address');
            $buyer->setNotify(true);

            $invoice->setBuyer($buyer);

            // Create the invoice on BitPay and get the redirect URL
            $invoice = LaravelBitpay::createInvoice($invoice);

            // Generate response similar to PayHere
            $data['redirect_url'] = $invoice->getUrl();
            $data['payment_id'] = $invoice->getId();
            $data['success'] = true;
            $data['message'] = 'Redirecting to BitPay...';

            Log::info('BitPay Invoice Created: ' . json_encode($data));

            return $data;
        } catch (\Exception $ex) {
            $data['success'] = false;
            $data['message'] = $ex->getMessage();
            Log::error('BitPay Error: ' . $data['message']);
            return $data;
        }
    }

    // Payment confirmation similar to PayHere, using signature verification
    public function paymentConfirmation($order_id)
    {
        $data['success'] = false;
        $data['data'] = null;

        // Get signature and encrypted data from the request (sent back from BitPay)
        $signature = request()->query('signature');
        $encryptedData = request()->query('data');

        try {
            // Decrypt the passed data (user ID and timestamp)
            $decryptedData = decrypt($encryptedData);
            list($userId, $timestamp) = explode('|', $decryptedData);

            // Verify the signature by comparing it with the expected signature
            $expectedSignature = hash_hmac('sha256', $userId . '|' . $order_id . '|' . $timestamp, $this->merchantToken);

            if (hash_equals($expectedSignature, $signature)) {
                $data['success'] = true;
                $data['data']['payment_status'] = 'success';
                $data['data']['payment_method'] = BITPAY;
            } else {
                $data['message'] = 'Signature mismatch';
            }
        } catch (\Exception $e) {
            $data['message'] = 'Invalid or tampered data';
            Log::error('BitPay Payment Verification Failed: ' . $data['message']);
        }

        return $data;
    }
}
