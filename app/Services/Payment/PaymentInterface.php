<?php

namespace App\Services\Payment;

interface PaymentInterface
{
    /**
     * Make a payment.
     *
     * @param float $amount
     * @return array
     */
    public function makePayment($amount): array;

    /**
     * Confirm a payment.
     *
     * @param string $paymentId
     * @param string|null $payerId
     * @return array
     */
    public function paymentConfirmation($paymentId, $payerId = null): array;

    /**
     * Save a product to the payment system.
     *
     * @param array $data
     * @return array
     */
    public function saveProduct($data): array;

    /**
     * Subscribe to a product or service.
     *
     * @param string $productId
     * @param array|null $data
     * @return array
     */
    public function subscribe($productId, $data = null): array;

    /**
     * Cancel an active subscription.
     *
     * @param string $subscriptionId
     * @param array|null $data
     * @return array
     */
    public function subscriptionCancel($subscriptionId, $data = null): array;

    /**
     * Get the remaining days of a subscription.
     *
     * @param string $subscriptionId
     * @param array|null $data
     * @return array
     */
    public function subscriptionRemainingDays($subscriptionId, $data = null): array;

    /**
     * Get the status of a subscription.
     *
     * @param string $subscriptionId
     * @param array|null $data
     * @return array
     */
    public function subscriptionStatus($subscriptionId, $data = null): array;

    /**
     * Get the renewal date of a subscription.
     *
     * @param string $subscriptionId
     * @param array|null $data
     * @return array
     */
    public function subscriptionRenewalDate($subscriptionId, $data = null): array;

    /**
     * Create a webhook for listening to payment events.
     *
     * @return array
     */
    public function createWebhook(): array;

    /**
     * Handle incoming webhook events.
     *
     * @param mixed $request
     * @return array
     */
    public function handleWebhook($request): array;
}
