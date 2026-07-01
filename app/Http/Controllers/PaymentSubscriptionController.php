<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckoutRequest;
use App\Models\Bank;
use App\Models\Currency;
use App\Models\EmailTemplate;
use App\Models\FileManager;
use App\Models\Gateway;
use App\Models\GatewayCurrency;
use App\Models\Package;
use App\Models\SubscriptionOrder;
use App\Models\User;
use App\Services\Payment\Payment;
use App\Services\Payment\StripeService;
use App\Services\SmsMail\MailService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentSubscriptionController extends Controller
{
    public function checkout(CheckoutRequest $request)
    {
        DB::beginTransaction();
        try {
            $user = User::where('role', USER_ROLE_ADMIN)->first();
            $durationType = $request->duration_type == PACKAGE_DURATION_TYPE_MONTHLY ? PACKAGE_DURATION_TYPE_MONTHLY : PACKAGE_DURATION_TYPE_YEARLY;
            $quantity = (int)$request->quantity > 0 ? $request->quantity : 1;
            $package = Package::findOrFail($request->package_id);
            $gateway = Gateway::where(['owner_user_id' => $user->id, 'slug' => $request->gateway, 'status' => ACTIVE])->firstOrFail();
            $gatewayCurrency = GatewayCurrency::where(['gateway_id' => $gateway->id, 'currency' => $request->currency])->firstOrFail();
            if ($gateway->slug == 'bank') {
                $bank = Bank::where(['owner_user_id' => $user->id, 'gateway_id' => $gateway->id, 'id' => $request->bank_id])->first();
                if (is_null($bank)) {
                    throw new Exception('Bank not found');
                }
                $bank_id = $bank->id;
                $bank_name = $bank->name;
                $bank_account_number = $bank->bank_account_number;
                $deposit_by = $request->deposit_by;
                $deposit_slip_id = null;
                if ($request->hasFile('bank_slip')) {
                    /*File Manager Call upload for Thumbnail Image*/
                    $newFile = new FileManager();
                    $upload = $newFile->upload('Order', $request->bank_slip);

                    if ($upload['status']) {
                        $deposit_slip_id = $upload['file']->id;
                        $upload['file']->origin_type = "App\Models\Order";
                        $upload['file']->save();
                    } else {
                        throw new Exception($upload['message']);
                    }
                    /*End*/
                } else {
                    throw new Exception('The Bank slip is required');
                }
                $order = $this->placeOrder($package, $durationType, $quantity, $gateway, $gatewayCurrency, null, $bank_id, $bank_name, $bank_account_number, $deposit_by, $deposit_slip_id); // new order create
                $order->deposit_slip_id = $deposit_slip_id;
                $order->save();

                $title = __("You have a new subscription package sell");
                $body = __("Please review the user's subscription details and ensure everything is in order");
                addNotification($title, $body, null, null, $user->id, auth()->id());

                DB::commit();

                return redirect()->route('owner.subscription.index')->with('success', __('Bank Details Sent Successfully! Wait for approval'));
            } elseif ($gateway->slug == 'cash') {
                $order = $this->placeOrder($package, $durationType, $quantity, $gateway, $gatewayCurrency); // new order create
                $order->save();

                $title = __("You have a new subscription package sell");
                $body = __("Please review the user's subscription details and ensure everything is in order");
                addNotification($title, $body, null, null, $user->id, auth()->id());

                DB::commit();
                return redirect()->route('owner.subscription.index')->with('success', __('Cash Payment Request Sent Successfully! Wait for approval'));
            } else {
                $order = $this->placeOrder($package, $durationType, $quantity, $gateway, $gatewayCurrency); // new order create
                DB::commit();
            }
            $object = [
                'id' => $order->id,
                'callback_url' => route('payment.subscription.verify'),
                'cancel_url' => route('payment.subscription.failed'),
                'currency' => $gatewayCurrency->currency,
                'type' => 'subscription'
            ];
            $productPrice = $package->subscriptionPrice->where('gateway_id', $gateway->id)->first();
            if ($productPrice) {
                $object['callback_url'] = route('payment.subscription.verify', ['subscription_success' => true]);
                $payment = new Payment($gateway->slug, $object);
                $planId = $durationType == PACKAGE_DURATION_TYPE_MONTHLY ? $productPrice->monthly_price_id : $productPrice->yearly_price_id;
                $responseData = $payment->subscribe($planId, ['package_id' => $package->id, 'package_gateway_price_id' => $productPrice->id]);
            } else {
                $payment = new Payment($gateway->slug, $object);
                $responseData = $payment->makePayment($order->total);
            }

            if ($responseData['success']) {
                $order->payment_id = $responseData['payment_id'];
                $order->save();
                return redirect($responseData['redirect_url']);
            } else {
                return redirect()->back()->with('error', $responseData['message']);
            }

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('owner.subscription.index')->with('error', __('Payment Failed!'));
        }
    }

    public function placeOrder($package, $durationType, $quantity, $gateway, $gatewayCurrency, $userId = null, $bank_id = null, $bank_name = null, $bank_account_number = null, $deposit_by = null, $deposit_slip_id = null)
    {
        $price = 0;
        $perPrice = 0;
        if ($durationType == PACKAGE_DURATION_TYPE_MONTHLY) {
            $price = $package->monthly_price;
            $perPrice = $package->per_monthly_price * $quantity;
        } else {
            $price = $package->yearly_price;
            $perPrice = $package->per_yearly_price * $quantity;
        }
        $total = $price + $perPrice;

        return SubscriptionOrder::create([
            'user_id' => $userId ?? auth()->id(),
            'package_id' => $package->id,
            'package_type' => $package->type,
            'quantity' => $quantity,
            'system_currency' => Currency::where('current_currency', ACTIVE)->first()->currency_code,
            'gateway_id' => $gateway->id,
            'duration_type' => $durationType,
            'gateway_currency' => $gatewayCurrency->currency,
            'amount' => $price,
            'subtotal' => $total,
            'total' => $total,
            'transaction_amount' => $total * $gatewayCurrency->conversion_rate,
            'conversion_rate' => $gatewayCurrency->conversion_rate,
            'payment_status' => ORDER_PAYMENT_STATUS_PENDING,
            'bank_id' => $bank_id,
            'bank_name' => $bank_name,
            'bank_account_number' => $bank_account_number,
            'deposit_by' => $deposit_by,
            'deposit_slip_id' => $deposit_slip_id
        ]);
    }

    public function verify(Request $request)
    {
        if ($request->subscription_success) {
        return redirect()->route('owner.subscription.index')->with('success', __('Payment Successful!'));
        }
        $order_id = $request->get('id', '');
        $payerId = $request->get('PayerID', NULL);
        $payment_id = $request->get('payment_id', NULL);

        $order = SubscriptionOrder::findOrFail($order_id);
        if ($order->status == ORDER_PAYMENT_STATUS_PAID) {
            return redirect()->route('owner.subscription.index')->with('error', __('Your order has been paid!'));
        }

        $gateway = Gateway::find($order->gateway_id);
        DB::beginTransaction();
        try {
            if ($order->gateway_id == $gateway->id && $gateway->slug == MERCADOPAGO) {
                $order->payment_id = $payment_id;
                $order->save();
            }

            $payment_id = $order->payment_id;

            $gatewayBasePayment = new Payment($gateway->slug, ['currency' => $order->gateway_currency, 'type' => 'subscription']);
            $payment_data = $gatewayBasePayment->paymentConfirmation($payment_id, $payerId);
            Log::info("payment_data");
            Log::info(json_encode($payment_data));
            if ($payment_data['success']) {
                if ($payment_data['data']['payment_status'] == 'success') {
                    $order->payment_status = ORDER_PAYMENT_STATUS_PAID;
                    $order->transaction_id = str_replace('-', '', uuid_create());
                    $order->save();
                    $package = Package::find($order->package_id);
                    $duration = 0;
                    if ($order->duration_type == PACKAGE_DURATION_TYPE_MONTHLY) {
                        $duration = 30;
                    } elseif ($order->duration_type == PACKAGE_DURATION_TYPE_YEARLY) {
                        $duration = 365;
                    }
                    Log::info("set package");
                    setUserPackage($order->user_id, $package, $duration, $order->quantity, $order->id);
                    Log::info("set package end");

                    DB::commit();
                    Log::info("success");
                    $title = __("You have a new invoice");
                    $body = __("Subscription payment verify successfully");
                    $adminUser = User::where('role', USER_ROLE_ADMIN)->first();
                    addNotification($title, $body, null, null, $adminUser->id, auth()->id());

                    if (getOption('send_email_status', 0) == ACTIVE) {
                        $emails = [$order->user->email];
                        $subject = __('Payment Successful!');
                        $title = __('Congratulations!');
                        $message = __('You have successfully been payment');
                        $ownerUserId = auth()->id();
                        $method = $gateway->slug;
                        $status = 'Paid';
                        $amount = $order->amount;

                        $mailService = new MailService;
                        $template = EmailTemplate::where('owner_user_id', $ownerUserId)->where('category', EMAIL_TEMPLATE_SUBSCRIPTION_SUCCESS)->where('status', ACTIVE)->first();
                        if ($template) {
                            $customizedFieldsArray = [
                                '{{amount}}' => $order->total,
                                '{{status}}' => $status,
                                '{{duration}}' => $duration,
                                '{{gateway}}' => $method,
                                '{{app_name}}' => getOption('app_name')
                            ];
                            $content = getEmailTemplate($template->body, $customizedFieldsArray);
                            $mailService->sendCustomizeMail($emails, $template->subject, $content);
                        } else {
                            $mailService->sendSubscriptionSuccessMail($emails, $subject, $message, $ownerUserId, $title, $method, $status, $amount, $duration);
                        }
                    }
                    Log::info("error 1");
                    return redirect()->route('owner.subscription.index')->with('success', __('Payment Successful!'));
                }else{
                    DB::rollBack();
                    Log::info("error 2");
                    return redirect()->route('owner.subscription.index')->with('error', __('Payment Failed!'));
                }
            } else {
                DB::rollBack();
                Log::info("error 3");
                return redirect()->route('owner.subscription.index')->with('error', __('Payment Failed!'));
            }
        } catch (\Exception $e) {
            Log::info($e->getMessage());
            DB::rollBack();
            return redirect()->route('owner.subscription.index')->with('error', __('Payment Failed!'));
        }
    }

    public function failed(Request $request)
    {
        return redirect()->route('owner.subscription.index')->with('error', __('Payment Failed!'));
    }

    public function webhook(Request $request)
    {
        // Retrieve the gateway based on the payment method (Stripe or PayPal)
        $userId = User::where('role', USER_ROLE_ADMIN)->first()->id;
        $gateway = Gateway::where(['owner_user_id' => $userId, 'slug' => $request->payment_method])->first();
        $gatewayCurrency = GatewayCurrency::where(['gateway_id' => $gateway->id])->first();

        if (!$gateway) {
            return response()->json(['success' => false, 'message' => 'Gateway not found']);
        }

        // Define the payment service object dynamically
        $object = [
            'type' => 'subscription',
            'currency' => $gatewayCurrency->currency,
        ];

        $paymentService = new Payment($request->payment_method, $object);

        // Handle the webhook request using the respective service (Stripe or PayPal)
        $response = $paymentService->handleWebhook($request);

        if ($response['success']) {
            // Determine whether the event is from Stripe or PayPal and handle it accordingly
            $event = $response['event'];

            if ($request->payment_method === 'stripe') {
                // Call Stripe specific webhook handler
                $this->stripeWebhook($event);
            } elseif ($request->payment_method === 'paypal') {
                // Call PayPal specific webhook handler
                $this->paypalWebhook($event);
            }

            return response()->json(['success' => true, 'message' => 'Webhook handled successfully']);
        } else {
            return response()->json(['success' => false, 'message' => $response['message']]);
        }
    }

    function stripeWebhook($event)
    {
        try {
            DB::beginTransaction();
            // Process the event based on its type
            switch ($event->type) {
                case 'invoice.created':
                    $response = $event->data->object;
                    $metaData = $response->subscription_details->metadata;
                    $planData = $response->lines->data[0]->plan;

                    $packageType = $planData->interval == 'month' ? PACKAGE_DURATION_TYPE_MONTHLY : PACKAGE_DURATION_TYPE_YEARLY;
                    $package = Package::where('id', $metaData->package_id)->first();

                    $price = $planData->interval == 'month' ? $package->monthly_price : $package->yearly_price;

                    if ($price * 100 <= $response->total) {
                        $payment = SubscriptionOrder::where(['user_id' => $metaData->user_id, 'payment_id' => $response->id])->first();
                        if (is_null($payment)) {
                            $userId = User::where('role', USER_ROLE_ADMIN)->first()->id;
                            $gateway = Gateway::where(['owner_user_id' => $userId, 'slug' => STRIPE, 'status' => ACTIVE])->firstOrFail();
                            $gatewayCurrency = GatewayCurrency::where(['gateway_id' => $gateway->id])->first();
                            $order = $this->placeOrder($package, $packageType, 1, $gateway, $gatewayCurrency, $metaData->user_id);
                            $order->payment_id = $response->id;
                            $order->save();
                        } else {
                            Log::info('--------***Already order found***------');
                            Log::info('--------***Check if invoice order already exist END***------');
                        }
                    } else {
                        Log::info('--------***Amount mismatch***------');
                        Log::info('--------***Webhook END***------');
                    }
                    DB::commit();
                    break;
                case 'invoice.payment_succeeded':
                    $response = $event->data->object;
                    $metaData = $response->subscription_details->metadata;
                    //check if the payment is there and in processing
                    Log::info('--------***Check if order exist or order status in processing START***------');
                    $order = SubscriptionOrder::where('payment_id', $response->id)->first();
                    if (!is_null($order) && $order->payment_status == ORDER_PAYMENT_STATUS_PENDING) {
                        Log::info('--------***Order found***------');
                        Log::info('--------***Order invoice verify START***------');
                        $order->payment_status = ORDER_PAYMENT_STATUS_PAID;
                        $order->transaction_id = str_replace('-', '', uuid_create());
                        $order->save();
                        $package = Package::find($order->package_id);
                        $duration = 0;
                        if ($order->duration_type == PACKAGE_DURATION_TYPE_MONTHLY) {
                            $duration = 30;
                        } elseif ($order->duration_type == PACKAGE_DURATION_TYPE_YEARLY) {
                            $duration = 365;
                        }

                        setUserPackage($metaData->user_id, $package, $duration, $order->quantity, $order->id);

                        DB::commit();
                        $title = __("You have a new invoice");
                        $body = __("Subscription payment verify successfully");
                        $adminUser = User::where('role', USER_ROLE_ADMIN)->first();
                        addNotification($title, $body, null, null, $adminUser->id, auth()->id());

                        if (getOption('send_email_status', 0) == ACTIVE) {
                            $emails = [$order->user->email];
                            $subject = __('Payment Successful!');
                            $title = __('Congratulations!');
                            $message = __('You have successfully been payment');
                            $ownerUserId = auth()->id();
                            $method = $order->gateway->slug;
                            $status = 'Paid';
                            $amount = $order->amount;

                            $mailService = new MailService;
                            $template = EmailTemplate::where('owner_user_id', $ownerUserId)->where('category', EMAIL_TEMPLATE_SUBSCRIPTION_SUCCESS)->where('status', ACTIVE)->first();
                            if ($template) {
                                $customizedFieldsArray = [
                                    '{{amount}}' => $order->total,
                                    '{{status}}' => $status,
                                    '{{duration}}' => $duration,
                                    '{{gateway}}' => $method,
                                    '{{app_name}}' => getOption('app_name')
                                ];
                                $content = getEmailTemplate($template->body, $customizedFieldsArray);
                                $mailService->sendCustomizeMail($emails, $template->subject, $content);
                            } else {
                                $mailService->sendSubscriptionSuccessMail($emails, $subject, $message, $ownerUserId, $title, $method, $status, $amount, $duration);
                            }
                        }
                        Log::info('--------***Order invoice verify END***------');
                    } else {
                        Log::info('--------***Order not found with that criteria***------');
                        Log::info('--------***Check if order exist or order status in processing END***------');
                    }
                    DB::commit();
                    break;
                // Add more cases for other event types as needed
                default:
                    // Handle unknown event types
                    break;
            }
        } catch (\Exception $e) {
            DB::rollBack();
            // Invalid payload
            Log::info('Stripe webhook error: ' . $e->getMessage() . ' Line: ' . $e->getLine() . ' File: ' . $e->getFile());
            Log::info('--------***Webhook Failed -- END***------');
        }

    }

    public function paypalWebhook($event)
    {
        // Handle PayPal specific events
        switch ($event['event_type']) {
            case 'PAYMENT.SALE.COMPLETED':
                $resource = $event['resource'];
                Log::info('Handling PayPal Payment Completed:', $resource);

                // Extract payment information from the webhook
                $paymentId = $resource['id'];
                $metaData = json_decode($resource['custom'], true); // Assuming 'custom_id' stores package_id and user_id

                // Find the subscription order using the payment ID or transaction ID
                $order = SubscriptionOrder::where('payment_id', $paymentId)->first();

                if (is_null($order)) {
                    // No order found, create a new one
                    $userId = $metaData['user_id'] ?? null;
                    $packageId = $metaData['package_id'] ?? null;
                    $package = Package::find($packageId);

                    if (is_null($package) || is_null($userId)) {
                        Log::error("Invalid metadata for PayPal event: " . json_encode($metaData));
                        return;
                    }

                    $packageType = $metaData['duration_type'] === 'monthly' ? PACKAGE_DURATION_TYPE_MONTHLY : PACKAGE_DURATION_TYPE_YEARLY;
                    $gateway = Gateway::where(['owner_user_id' => $userId, 'slug' => 'paypal', 'status' => ACTIVE])->firstOrFail();
                    $gatewayCurrency = GatewayCurrency::where(['gateway_id' => $gateway->id])->first();

                    // Create new order
                    $order = $this->placeOrder($package, $packageType, 1, $gateway, $gatewayCurrency, $userId);
                    $order->payment_id = $paymentId;
                    $order->save();
                }

                // If order exists and payment is pending, mark it as paid
                if ($order && $order->payment_status == ORDER_PAYMENT_STATUS_PENDING) {
                    $order->payment_status = ORDER_PAYMENT_STATUS_PAID;
                    $order->transaction_id = $paymentId;  // PayPal Transaction ID
                    $order->save();

                    // Activate user package
                    $package = Package::find($order->package_id);
                    $duration = $order->duration_type == PACKAGE_DURATION_TYPE_MONTHLY ? 30 : 365;
                    setUserPackage($metaData['user_id'], $package, $duration, $order->quantity, $order->id);

                    // Notify admin about the new invoice
                    $title = __("You have a new invoice");
                    $body = __("Subscription payment verified successfully");
                    $adminUser = User::where('role', USER_ROLE_ADMIN)->first();
                    addNotification($title, $body, null, null, $adminUser->id, $order->user_id);

                    // Send payment success email to the user
                    if (getOption('send_email_status', 0) == ACTIVE) {
                        $emails = [$order->user->email];
                        $subject = __('Payment Successful!');
                        $title = __('Congratulations!');
                        $message = __('You have successfully made a payment');
                        $ownerUserId = $order->user_id;
                        $method = $order->gateway->slug;
                        $status = 'Paid';
                        $amount = $order->amount;

                        $mailService = new MailService;
                        $template = EmailTemplate::where('owner_user_id', $ownerUserId)
                            ->where('category', EMAIL_TEMPLATE_SUBSCRIPTION_SUCCESS)
                            ->where('status', ACTIVE)
                            ->first();

                        if ($template) {
                            $customizedFieldsArray = [
                                '{{amount}}' => $order->total,
                                '{{status}}' => $status,
                                '{{duration}}' => $duration,
                                '{{gateway}}' => $method,
                                '{{app_name}}' => getOption('app_name')
                            ];
                            $content = getEmailTemplate($template->body, $customizedFieldsArray);
                            $mailService->sendCustomizeMail($emails, $template->subject, $content);
                        } else {
                            $mailService->sendSubscriptionSuccessMail($emails, $subject, $message, $ownerUserId, $title, $method, $status, $amount, $duration);
                        }
                    }

                    Log::info('Payment successfully completed for order ID: ' . $order->id);
                } else {
                    Log::warning('Order not found or already processed for payment ID: ' . $paymentId);
                }
                break;
            default:
                // Handle unknown event types
                break;
        }
    }

}
