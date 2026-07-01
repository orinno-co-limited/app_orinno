<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
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
use App\Services\SmsMail\MailService;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentSubscriptionController extends Controller
{
    use ResponseTrait;
    public function checkout(CheckoutRequest $request)
    {
        DB::beginTransaction();
        try {
            $user = User::where('role', USER_ROLE_ADMIN)->first();
            $durationType = $request->duration_type == PACKAGE_DURATION_TYPE_MONTHLY ? PACKAGE_DURATION_TYPE_MONTHLY : PACKAGE_DURATION_TYPE_YEARLY;
            $quantity = (int) $request->quantity > 0 ? $request->quantity : 1;
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
                    $newFile = new FileManager();
                    $upload = $newFile->upload('Order', $request->bank_slip);

                    if ($upload['status']) {
                        $deposit_slip_id = $upload['file']->id;
                        $upload['file']->origin_type = "App\Models\Order";
                        $upload['file']->save();
                    } else {
                        throw new Exception($upload['message']);
                    }
                } else {
                    throw new Exception('The Bank slip is required');
                }
                $order = $this->placeOrder($package, $durationType, $quantity, $gateway, $gatewayCurrency, $bank_id, $bank_name, $bank_account_number, $deposit_by, $deposit_slip_id);
                $order->deposit_slip_id = $deposit_slip_id;
                $order->save();
                DB::commit();
                return $this->success([], __('Bank Details Sent Successfully! Wait for approval'));
            } elseif ($gateway->slug == 'cash') {
                $order = $this->placeOrder($package, $durationType, $quantity, $gateway, $gatewayCurrency);
                $order->save();
                DB::commit();
                return $this->success([], __('Cash Payment Request Sent Successfully! Wait for approval'));
            } else {
                $order = $this->placeOrder($package, $durationType, $quantity, $gateway, $gatewayCurrency);
                DB::commit();
                $object = [
                    'id' => $order->id,
                    'callback_url' => url('api/payment-subscription/verify'),
                    'cancel_url' => url('api/payment-subscription/failed'),
                    'currency' => $gatewayCurrency->currency,
                    'type' => 'subscription'
                ];

                $payment = new Payment($gateway->slug, $object);
                $responseData = $payment->makePayment($order->total);
                if ($responseData['success']) {
                    $order->payment_id = $responseData['payment_id'];
                    $order->save();
                    return $this->success(['redirect_url' => $responseData['redirect_url']]);
                } else {
                    throw new Exception($responseData['message']);
                }
            }
        } catch (Exception $e) {
            DB::rollBack();
            return $this->error([], $e->getMessage());
        }
    }

    public function placeOrder($package, $durationType, $quantity, $gateway, $gatewayCurrency, $bank_id = null, $bank_name = null, $bank_account_number = null, $deposit_by = null, $deposit_slip_id = null)
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
            'user_id' => auth()->id(),
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
        $order_id = $request->get('id', '');
        $payerId = $request->get('PayerID', NULL);
        $payment_id = $request->get('payment_id', NULL);

        $order = SubscriptionOrder::findOrFail($order_id);
        if ($order->status == ORDER_PAYMENT_STATUS_PAID) {
            return $this->success([], __('Your order has been paid!'));
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

                    setUserPackage(auth()->id(), $package, $duration, $order->quantity, $order->id);

                    DB::commit();
                    if (getOption('send_email_status', 0) == ACTIVE) {
                        $emails = [$order->user->email];
                        $subject = __('Payment Successful!');
                        $title = __('Congratulations!');
                        $message = __('You have successfully been payment');
                        $ownerUserId = auth()->id();
                        $method = $gateway->slug;
                        $status = 'Paid';
                        $amount = $order->amount;
                        $duration = $duration;

                        $mailService = new MailService;
                        $template = EmailTemplate::where('owner_user_id', $ownerUserId)->where('category', EMAIL_TEMPLATE_SUBSCRIPTION_SUCCESS)->where('status', ACTIVE)->first();
                        if ($template) {
                            $customizedFieldsArray = [
                                '{{amount}}' => $order->amount,
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
                    return $this->success([], __('Payment Successful!'));
                }
            } else {
                return $this->error([], __('Payment Failed!'));
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error([], __('Payment Failed!'));
        }
    }

    public function failed(Request $request)
    {
        return $this->error([], __('Payment Failed!'));
    }
}
