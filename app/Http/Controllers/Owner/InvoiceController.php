<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Controller;
use App\Http\Requests\InvoiceRequest;
use App\Http\Requests\PaymentStatusRequest;
use App\Http\Requests\NotificationRequest;
use App\Models\Bank;
use App\Models\Gateway;
use App\Models\GatewayCurrency;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\Tenant;
use App\Services\GatewayService;
use App\Services\InvoiceService;
use App\Services\Payment\Payment;
use App\Services\PropertyService;
use App\Services\TenantService;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    use ResponseTrait;

    public $invoiceService;
    public $tenantService;
    public $propertyService;

    public function __construct()
    {
        $this->invoiceService = new InvoiceService();
        $this->tenantService = new TenantService();
        $this->propertyService = new PropertyService;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            return $this->invoiceService->getAllInvoicesData($request);
        } else {
            $responseData = $this->invoiceService->getAllInvoices();
            $gatewayService = new GatewayService();
            $responseData['properties'] = $this->propertyService->getAll();
            $responseData['gateways'] = $gatewayService->getActiveAll(getOwnerUserId());
            $responseData['banks'] = Bank::where('owner_user_id', getOwnerUserId())->where('status', ACTIVE)->get();;
            return view('owner.invoice.index')->with($responseData);
        }
    }

    public function paidInvoiceIndex(Request $request)
    {
        if ($request->ajax()) {
            return $this->invoiceService->getPaidInvoicesData($request);
        }
    }

    public function pendingInvoiceIndex(Request $request)
    {
        if ($request->ajax()) {
            return $this->invoiceService->getPendingInvoicesData($request);
        }
    }

    public function bankPendingInvoice(Request $request)
    {
        if ($request->ajax()) {
            return $this->invoiceService->getBankPendingInvoicesData($request);
        }
    }

    public function overDueInvoiceIndex(Request $request)
    {
        if ($request->ajax()) {
            return $this->invoiceService->getOverDueInvoicesData($request);
        }
    }

    public function details($id)
    {
        $data['invoice'] = $this->invoiceService->getById($id);

        if ($data['invoice']->due_date < date('Y-m-d')) {
            $tenant = Tenant::findOrFail($data['invoice']->tenant_id);
            if ($tenant->late_fee_type == TYPE_PERCENTAGE) {
                $lateFeeAmount = $data['invoice']->amount * $tenant->late_fee * 0.01;
            } else {
                $lateFeeAmount =  $tenant->late_fee;
            }
            $data['invoice']->update(['late_fee' => $lateFeeAmount]);
        }

        $data['items'] = $this->invoiceService->getItemsByInvoiceId($id);
        $data['tenant'] = $this->tenantService->getDetailsById($data['invoice']->tenant_id);
        $data['order'] = $this->invoiceService->getOrderById($data['invoice']->order_id);
        $data['owner'] = $this->invoiceService->ownerInfo(auth()->id());
        return $this->success($data);
    }

    public function print($id)
    {
        $data['invoice'] = $this->invoiceService->getById($id);
        $data['items'] = $this->invoiceService->getItemsByInvoiceId($id);
        $data['owner'] = $this->invoiceService->ownerInfo(getOwnerUserId());
        $data['tenant'] = $this->tenantService->getDetailsById($data['invoice']->tenant_id);
        $data['order'] = $this->invoiceService->getOrderById($data['invoice']->order_id);
        return view('tenant.invoices.print', $data);
    }

    public function pay(Request $request, $id)
    {
        $request->validate([
            'gateway_id' => 'required',
            'currency_id' => 'required',
            'transactionId' => 'required',
        ]);

        $invoice = $this->invoiceService->getById($id);
        $gateway = Gateway::where(['owner_user_id' => getOwnerUserId(), 'id' => $request->gateway_id, 'status' => ACTIVE])->firstOrFail();
        $gatewayCurrency = GatewayCurrency::where(['owner_user_id' => getOwnerUserId(), 'gateway_id' => $gateway->id, 'id' => $request->currency_id])->firstOrFail();

        $payment = new PaymentController();
        if ($gateway->slug == 'bank') {
            $bank = Bank::where(['gateway_id' => $gateway->id, 'id' => $request->bank_id])->firstOrFail();
            $bank_id = $bank->id;
            $bank_name = $bank->name;
            $bank_account_number = $bank->bank_account_number;
            $order = $payment->placeOrder($invoice, $gateway, $gatewayCurrency, $bank_id, $bank_name, $bank_account_number);
        } else {
            $order = $payment->placeOrder($invoice, $gateway, $gatewayCurrency);
        }

        DB::beginTransaction();
        try {
            $order->payment_id = $request->transactionId;
            $order->payment_status = INVOICE_STATUS_PAID;
            $order->save();
            $invoice = Invoice::find($order->invoice_id);
            $invoice->status = INVOICE_STATUS_PAID;
            $invoice->order_id = $order->id;
            $invoice->save();
            DB::commit();
            return $this->success([], __('Payment Successful!'));
        } catch (Exception $e) {
            DB::rollBack();
            return $this->error([], __('Payment Failed!'));
        }
    }

    public function store(InvoiceRequest $request)
    {
        return $this->invoiceService->store($request);
    }

    public function paymentStatus(PaymentStatusRequest $request)
    {
        return $this->invoiceService->paymentStatusChange($request);
    }

    public function destroy($id)
    {
        return $this->invoiceService->destroy($id);
    }

    public function types()
    {
        $invoiceTypes = $this->invoiceService->types();
        return $this->success($invoiceTypes);
    }

    public function sendNotification(NotificationRequest $request)
    {
        try {
            if ($request->notification_type == NOTIFICATION_TYPE_SINGLE) {
                return $this->invoiceService->sendSingleNotification($request);
            } elseif ($request->notification_type == NOTIFICATION_TYPE_MULTIPLE) {
                return $this->invoiceService->sendMultiNotification($request);
            }
        } catch (Exception $e) {
            return $this->error([]);
        }
    }

    public function getCurrencyByGateway(Request $request)
    {
        $currencies = GatewayCurrency::where('owner_user_id', getOwnerUserId())->where('gateway_id', $request->id)->get();
        foreach ($currencies as $currency) {
            $currency->symbol = $currency->symbol;
        }
        $data = $currencies?->makeHidden(['created_at', 'updated_at', 'deleted_at', 'gateway_id', 'owner_user_id']);

        return $this->success($data);
    }
}
