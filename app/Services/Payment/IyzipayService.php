<?php

namespace App\Services\Payment;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Iyzipay\Model\Address;
use Iyzipay\Model\BasketItem;
use Iyzipay\Model\BasketItemType;
use Iyzipay\Model\Buyer;
use Iyzipay\Model\Locale;
use Iyzipay\Model\PaymentGroup;
use Iyzipay\Model\PayWithIyzicoInitialize;
use Iyzipay\Options;
use Iyzipay\Request\CreatePayWithIyzicoInitializeRequest;
use Illuminate\Support\Str;

class IyzipayService extends BasePaymentService
{
    private $apiUrl;
    private $apiKey;
    private $apiSecret;
    private $IOptions;

    public function __construct($method, $object)
    {
        parent::__construct($method, $object);
        $this->apiUrl = $this->gateway->mode == GATEWAY_MODE_LIVE ? 'https://api.iyzipay.com' : 'https://sandbox-api.iyzipay.com';
        $this->apiKey = $this->gateway->key;
        $this->apiSecret = $this->gateway->secret;

        $this->IOptions = new Options();
        $this->IOptions->setApiKey($this->apiKey);
        $this->IOptions->setSecretKey($this->apiSecret);
        $this->IOptions->setBaseUrl($this->apiUrl);

        $this->locale = Locale::EN;
    }

    public function makePayment($amount)
    {
        $data['success'] = false;
        $data['redirect_url'] = '';
        $data['payment_id'] = '';
        $data['message'] = SOMETHING_WENT_WRONG;
        $this->setAmount($amount);

        try {
            $IForm = new CreatePayWithIyzicoInitializeRequest();
            $IForm->setLocale($this->locale);
            $IForm->setConversationId(Str::random(16));
            $IForm->setPrice($this->amount);
            $IForm->setPaidPrice($this->amount);
            $IForm->setCurrency($this->currency);
            $IForm->setBasketId(Str::random(16));
            $IForm->setPaymentGroup(PaymentGroup::PRODUCT);
            $IForm->setCallbackUrl($this->callbackUrl);

            // Set user or default values
            $user = Auth::check() ? Auth::user() : null;

            // Buyer Information
            $IBuyer = new Buyer();
            $IBuyer->setId(Str::random(16));
            $IBuyer->setName($user->name  ?? 'Guest');
            $IBuyer->setSurname($user->name  ?? 'User');
            $IBuyer->setEmail($user->email  ?? 'guest@example.com');
            $IBuyer->setIdentityNumber(Str::random(16));  // Random identity number for non-authenticated users
            $IBuyer->setRegistrationAddress($user->address  ?? 'Default Address');
            $IBuyer->setIp($_SERVER["REMOTE_ADDR"]);
            $IBuyer->setCity($user->city  ?? 'Default City');
            $IBuyer->setCountry($user->country  ?? 'Default Country');
            $IBuyer->setZipCode($user->postal_code  ?? '00000');
            $IForm->setBuyer($IBuyer);

            // Shipping and Billing Addresses
            $IShipping = new Address();
            $IShipping->setContactName($user->name  ?? 'Guest');
            $IShipping->setCity($user->city  ?? 'Default City');
            $IShipping->setCountry($user->country  ?? 'Default Country');
            $IShipping->setZipCode($user->postal_code  ?? '00000');
            $IShipping->setAddress($user->address  ?? 'Default Address');
            $IForm->setShippingAddress($IShipping);

            $IBilling = new Address();
            $IBilling->setContactName($user->name  ?? 'Guest');
            $IBilling->setCity($user->city  ?? 'Default City');
            $IBilling->setCountry($user->country  ?? 'Default Country');
            $IBilling->setZipCode($user->postal_code  ?? '00000');
            $IBilling->setAddress($user->address ?? 'Default Address');
            $IForm->setBillingAddress($IBilling);

            // Basket Items
            $FBasketItems = new BasketItem();
            $FBasketItems->setId(Str::random(16));
            $FBasketItems->setName(($user ? $user->name : 'Guest') . '\'s basket');
            $FBasketItems->setCategory1('General');
            $FBasketItems->setItemType(BasketItemType::VIRTUAL);
            $FBasketItems->setPrice($this->amount);
            $IForm->setBasketItems([$FBasketItems]);

            $payWithIyzicoInitialize = PayWithIyzicoInitialize::create($IForm, $this->IOptions);

            if ($payWithIyzicoInitialize->getStatus() == 'success') {
                $data['redirect_url'] = $payWithIyzicoInitialize->getPayWithIyzicoPageUrl();
                $data['payment_id'] = $IForm->getConversationId();
                $data['success'] = true;
            }

            return $data;
        } catch (\Exception $ex) {
            $data['message'] = $ex->getMessage();
            Log::error('Iyzico Payment Error: ', $data);
            return $data;
        }
    }

    public function paymentConfirmation($payment_id)
    {
        $data['success'] = false;
        $data['data'] = null;

        try {
            $request2 = new \Iyzipay\Request\RetrievePayWithIyzicoRequest();
            $request2->setLocale($this->locale);
            $request2->setConversationId($payment_id);
            $request2->setToken(request()->token);

            $checkoutForm = \Iyzipay\Model\PayWithIyzico::retrieve($request2, $this->IOptions);

            if ($checkoutForm->getStatus() == 'success') {
                $data['success'] = true;
                $data['data']['amount'] = $checkoutForm->getPaidPrice();
                $data['data']['currency'] = $checkoutForm->getCurrency();
                $data['data']['payment_status'] = 'success';
                $data['data']['payment_method'] = IYZICO;
            } else {
                $data['data']['payment_status'] = 'unpaid';
                $data['data']['payment_method'] = IYZICO;
            }

            return $data;
        } catch (\Exception $ex) {
            $data['message'] = $ex->getMessage();
            Log::error('Iyzico Payment Confirmation Error: ', $data);
            return $data;
        }
    }
}
