<?php

namespace App\Services\Payment;

use Illuminate\Support\Facades\Log;
use Stripe\StripeClient;
use Stripe\Webhook;
use Exception;

class StripeService extends BasePaymentService implements PaymentInterface
{
    protected $stripeClient;

    public function __construct($method, $object)
    {
        parent::__construct($method, $object);
        $this->stripeClient = new StripeClient($this->gateway->key);
    }

    /**
     * Make a payment using Stripe.
     *
     * @param float $amount
     * @return array
     */
    public function makePayment($amount): array
    {
        $this->setAmount($amount);
        $data = [
            'success' => false,
            'redirect_url' => '',
            'payment_id' => '',
            'message' => __('Something went wrong.'),
        ];

        try {
            $payment = $this->stripeClient->checkout->sessions->create([
                'success_url' => $this->callbackUrl,
                'cancel_url' => $this->cancelUrl,
                'line_items' => [
                    [
                        'price_data' => [
                            'currency' => $this->currency,
                            'product_data' => [
                                'name' => 'Amount',
                            ],
                            'unit_amount' => $this->amount * 100,
                        ],
                        'quantity' => 1,
                    ]
                ],
                'mode' => 'payment',
            ]);

            Log::info(json_encode($payment));

            if ($payment->status === 'open') {
                $data['payment_id'] = $payment->id;
                $data['success'] = true;
                $data['redirect_url'] = $payment->url;
            }

        } catch (Exception $ex) {
            $data['message'] = $ex->getMessage();
            Log::error('Stripe Payment Error: ' . $ex->getMessage());
        }

        return $data;
    }

    /**
     * Confirm a payment using Stripe.
     *
     * @param string $paymentId
     * @param string|null $payerId
     * @return array
     */
    public function paymentConfirmation($paymentId, $payerId = null): array
    {
        $data = ['data' => null];

        try {
            Log::info("------Payment Confirmation----");
            Log::info($paymentId);
            $payment = $this->stripeClient->checkout->sessions->retrieve($paymentId, []);

            Log::info(json_encode($payment));

            if ($payment->payment_status === 'paid') {
                $data['success'] = true;
                $data['data'] = [
                    'amount' => $payment->amount_total / 100,
                    'currency' => $payment->currency,
                    'payment_status' => 'success',
                    'payment_method' => STRIPE,
                ];
            } else {
                $data['success'] = false;
                $data['data'] = [
                    'amount' => $payment->amount_total / 100,
                    'currency' => $payment->currency,
                    'payment_status' => 'unpaid',
                    'payment_method' => STRIPE,
                ];
            }

        } catch (Exception $ex) {
            $data['message'] = $ex->getMessage();
            Log::error('Stripe Payment Confirmation Error: ' . $ex->getMessage());
        }

        return $data;
    }

    /**
     * Save or update prices in Stripe (deactivate old price and product only if price or name is different).
     *
     * @param array $data
     * @return array
     */
    public function saveProduct($data): array
    {
        try {
            $response = [];

            // Handle monthly price
            if (isset($data['monthly_price'])) {
                if (isset($data['monthlyPriceId']) && $data['monthlyPriceId']) {
                    // Retrieve the old monthly price and product
                    $oldMonthlyPrice = $this->stripeClient->prices->retrieve($data['monthlyPriceId']);
                    $oldProduct = $this->stripeClient->products->retrieve($oldMonthlyPrice->product);

                    // Check if the price or product name has changed
                    if ($oldMonthlyPrice->unit_amount != $data['monthly_price'] || $oldProduct->name != $data['name']) {
                        // Deactivate the old monthly price and product
                        $this->stripeClient->prices->update($data['monthlyPriceId'], [
                            'active' => false,
                        ]);
                        $this->stripeClient->products->update($oldMonthlyPrice->product, [
                            'active' => false,
                        ]);

                        // Create a new monthly price with a new product
                        $response['monthly_price_id'] = $this->stripeClient->prices->create([
                            'currency' => $this->currency,
                            'unit_amount' => $data['monthly_price'],
                            'recurring' => ['interval' => 'month'],
                            'product_data' => [
                                'name' => $data['name'],
                                'active' => true // The new product is active
                            ]
                        ])->id;
                    } else {
                        // Reuse the existing price and product if unchanged
                        $response['monthly_price_id'] = $data['monthlyPriceId'];
                    }
                } else {
                    // No existing price, create a new one
                    $response['monthly_price_id'] = $this->stripeClient->prices->create([
                        'currency' => $this->currency,
                        'unit_amount' => $data['monthly_price'],
                        'recurring' => ['interval' => 'month'],
                        'product_data' => [
                            'name' => $data['name'],
                            'active' => true // The new product is active
                        ]
                    ])->id;
                }
            }

            // Handle yearly price
            if (isset($data['yearly_price'])) {
                if (isset($data['yearlyPriceId']) && $data['yearlyPriceId']) {
                    // Retrieve the old yearly price and product
                    $oldYearlyPrice = $this->stripeClient->prices->retrieve($data['yearlyPriceId']);
                    $oldProduct = $this->stripeClient->products->retrieve($oldYearlyPrice->product);

                    // Check if the price or product name has changed
                    if ($oldYearlyPrice->unit_amount != $data['yearly_price'] || $oldProduct->name != $data['name']) {
                        // Deactivate the old yearly price and product
                        $this->stripeClient->prices->update($data['yearlyPriceId'], [
                            'active' => false,
                        ]);
                        $this->stripeClient->products->update($oldYearlyPrice->product, [
                            'active' => false,
                        ]);

                        // Create a new yearly price with a new product
                        $response['yearly_price_id'] = $this->stripeClient->prices->create([
                            'currency' => $this->currency,
                            'unit_amount' => $data['yearly_price'],
                            'recurring' => ['interval' => 'year'],
                            'product_data' => [
                                'name' => $data['name'],
                                'active' => true // The new product is active
                            ]
                        ])->id;
                    } else {
                        // Reuse the existing price and product if unchanged
                        $response['yearly_price_id'] = $data['yearlyPriceId'];
                    }
                } else {
                    // No existing price, create a new one
                    $response['yearly_price_id'] = $this->stripeClient->prices->create([
                        'currency' => $this->currency,
                        'unit_amount' => $data['yearly_price'],
                        'recurring' => ['interval' => 'year'],
                        'product_data' => [
                            'name' => $data['name'],
                            'active' => true // The new product is active
                        ]
                    ])->id;
                }
            }

            $this->createWebhook();

            Log::info('Prices and products saved or updated in Stripe: ', $data);
            return ['success' => true, 'data' => $response, 'message' => 'Prices and products saved or updated'];
        } catch (Exception $ex) {
            Log::error('Stripe Price/Product Save/Update Error: ' . $ex->getMessage());
            return ['success' => false, 'message' => $ex->getMessage()];
        }
    }



    /**
     * Subscribe to a product or service using Stripe Checkout.
     *
     * @param string $productId
     * @param array|null $data
     * @return array
     */
    public function subscribe($productId, $data = null): array
    {
        $response = [
            'success' => false,
            'redirect_url' => '',
            'subscription_id' => '',
            'message' => __('Something went wrong'),
        ];

        try {
            $authUser = auth()->user();

            // Create a new subscription using Stripe Checkout
            $payment = $this->stripeClient->checkout->sessions->create([
                'success_url' => $this->callbackUrl,
                'cancel_url' => $this->cancelUrl,
                'subscription_data' => [
                    'metadata' => [
                        'package_gateway_price_id' => $data['package_gateway_price_id'],
                        'package_id' => $data['package_id'],
                        'user_id' => $authUser->id,
                    ]
                ],
                'line_items' => [
                    [
                        'price' => $productId, // Stripe Price ID
                        'quantity' => 1,
                    ]
                ],
                'mode' => 'subscription',
            ]);

            // Check the payment session and return the result
            if ($payment->status == 'open') {
                $response['subscription_id'] = $payment->subscription ?? '';
                $response['payment_id'] = $payment->payment_intent ?? '';
                $response['success'] = true;
                $response['redirect_url'] = $payment->url;
            }

        } catch (\Exception $ex) {
            $response['message'] = $ex->getMessage();
            Log::error('Stripe Subscription Error: ' . $ex->getMessage());
        }

        return $response;
    }


    /**
     * Cancel an active subscription using Stripe.
     *
     * @param string $subscriptionId
     * @param array|null $data
     * @return array
     */
    public function subscriptionCancel($subscriptionId, $data = null): array
    {
        try {
            $this->stripeClient->subscriptions->cancel($subscriptionId);
            Log::info('Subscription cancelled: ' . $subscriptionId);
            return ['success' => true, 'message' => 'Subscription cancelled'];
        } catch (Exception $ex) {
            Log::error('Stripe Subscription Cancel Error: ' . $ex->getMessage());
            return ['success' => false, 'message' => $ex->getMessage()];
        }
    }

    /**
     * Get the remaining days of a subscription.
     *
     * @param string $subscriptionId
     * @param array|null $data
     * @return array
     */
    public function subscriptionRemainingDays($subscriptionId, $data = null): array
    {
        try {
            $subscription = $this->stripeClient->subscriptions->retrieve($subscriptionId);
            $remainingDays = (int)((strtotime($subscription->current_period_end) - time()) / (60 * 60 * 24));

            Log::info('Remaining days for subscription: ' . $subscriptionId);
            return ['success' => true, 'days_remaining' => $remainingDays];
        } catch (Exception $ex) {
            Log::error('Stripe Subscription Remaining Days Error: ' . $ex->getMessage());
            return ['success' => false, 'message' => $ex->getMessage()];
        }
    }

    /**
     * Get the status of a subscription using Stripe.
     *
     * @param string $subscriptionId
     * @param array|null $data
     * @return array
     */
    public function subscriptionStatus($subscriptionId, $data = null): array
    {
        try {
            $subscription = $this->stripeClient->subscriptions->retrieve($subscriptionId);
            $status = $subscription->status;

            Log::info('Subscription status for: ' . $subscriptionId);
            return ['success' => true, 'status' => $status];
        } catch (Exception $ex) {
            Log::error('Stripe Subscription Status Error: ' . $ex->getMessage());
            return ['success' => false, 'message' => $ex->getMessage()];
        }
    }

    /**
     * Get the renewal date of a subscription using Stripe.
     *
     * @param string $subscriptionId
     * @param array|null $data
     * @return array
     */
    public function subscriptionRenewalDate($subscriptionId, $data = null): array
    {
        try {
            $subscription = $this->stripeClient->subscriptions->retrieve($subscriptionId);
            $renewalDate = date('Y-m-d', $subscription->current_period_end);

            Log::info('Renewal date for subscription: ' . $subscriptionId);
            return ['success' => true, 'renewal_date' => $renewalDate];
        } catch (Exception $ex) {
            Log::error('Stripe Subscription Renewal Date Error: ' . $ex->getMessage());
            return ['success' => false, 'message' => $ex->getMessage()];
        }
    }

    /**
     * Create a webhook for Stripe, but only if it doesn't already exist.
     *
     * @return array
     */
    public function createWebhook(): array
    {
        try {
            // First, check if the webhook with the same URL already exists
            $webhooks = $this->stripeClient->webhookEndpoints->all();
            foreach ($webhooks->data as $existingWebhook) {
                if ($existingWebhook->url === $this->webhookUrl) {
                    // Webhook with the same URL already exists, return success with its ID
                    Log::info('Stripe webhook already exists: ' . $existingWebhook->id);
                    return ['success' => true, 'webhook_id' => $existingWebhook->id];
                }
            }

            // If we reach here, it means no webhook exists with the same URL, so we create a new one
            $webhook = $this->stripeClient->webhookEndpoints->create([
                'url' => $this->webhookUrl,
                'enabled_events' => ['invoice.created', 'invoice.payment_succeeded'],
            ]);

            $this->gateway->update(['url' => $webhook->secret]);

            Log::info('Stripe webhook created: ' . $webhook->id);
            return ['success' => true, 'webhook_id' => $webhook->id];

        } catch (Exception $ex) {
            Log::error('Stripe Webhook Creation Error: ' . $ex->getMessage());
            return ['success' => false, 'message' => $ex->getMessage()];
        }
    }


    /**
     * Handle incoming webhook events from Stripe.
     *
     * @param mixed $request
     * @return array
     */
    public function handleWebhook($request): array
    {
        try {
            $payload = $request->getContent();
            $signature = $request->header('Stripe-Signature');
            // Verify the webhook signature
            $event = \Stripe\Webhook::constructEvent($payload, $signature, $this->gateway->url);

            // Return the verified event
            return [
                'success' => true,
                'event' => $event
            ];
        } catch (\UnexpectedValueException $e) {
            // Invalid payload
            Log::info('Invalid payload: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => 'Invalid payload'
            ];
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            // Invalid signature
            Log::info('Invalid signature: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => 'Invalid signature'
            ];
        }
    }
}
