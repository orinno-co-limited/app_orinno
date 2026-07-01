<?php

/**
 * payment config with payment list
 * Created by Md. Abdullah <abdullah001rti@gmail.com>.
 */

return [
    'methods' => [
        'paypal' => 'App\PaymentProvider\PaypalProvider',
        'stripe' => 'App\PaymentProvider\StripeProvider',
        'bank' => 'App\PaymentProvider\BankDepositProvider',
        'manual' => 'App\PaymentProvider\ManualProvider',
    ]
];