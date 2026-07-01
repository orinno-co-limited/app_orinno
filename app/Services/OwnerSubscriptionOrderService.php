<?php

namespace App\Services;

use App\Models\OwnerPackage;
use App\Models\Package;
use App\Models\SubscriptionOrder;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Support\Facades\DB;

class OwnerSubscriptionOrderService
{
    use ResponseTrait;

    public function getAllData($request)
    {
        $orders = SubscriptionOrder::query()
            ->join('packages', 'subscription_orders.package_id', '=', 'packages.id')
            ->join('gateways', 'subscription_orders.gateway_id', '=', 'gateways.id')
            ->join('users', 'subscription_orders.user_id', '=', 'users.id')
            ->whereNull('users.deleted_at')
            ->leftJoin('file_managers', ['subscription_orders.deposit_slip_id' => 'file_managers.id', 'file_managers.origin_type' => (DB::raw("'App\\\Models\\\Order'"))])
            ->select(['subscription_orders.*', 'packages.name as packageName', 'gateways.title as gatewayTitle', 'gateways.slug as gatewaySlug', 'file_managers.file_name', 'file_managers.folder_name', 'users.first_name', 'users.last_name', 'users.email'])
            ->orderByDesc('subscription_orders.id');

        return datatables($orders)
            ->addIndexColumn()
            ->addColumn('package', function ($order) {
                return '<h6>' . $order->packageName . '</h6>';
            })
            ->addColumn('name', function ($order) {
                return $order->first_name . ' ' . $order->last_name . '(' . $order->email . ')';
            })
            ->addColumn('date', function ($order) {
                return $order->created_at->format('Y-m-d h:i');
            })
            ->addColumn('amount', function ($order) {
                return currencyPrice($order->total);
            })
            ->addColumn('gateway', function ($order) {
                if ($order->gatewaySlug == 'bank') {
                    return '<a href="' . getFileUrl($order->folder_name, $order->file_name) . '" title="Bank slip download" download>' . $order->gatewayTitle . '</a>';
                }
                return $order->gatewayTitle;
            })
            ->addColumn('status', function ($order) {
                if ($order->payment_status == ORDER_PAYMENT_STATUS_PAID) {
                    return '<div class="status-btn status-btn-blue font-13 radius-4">Paid</div>';
                } elseif ($order->payment_status == ORDER_PAYMENT_STATUS_PENDING) {
                    return '<div class="status-btn status-btn-red font-13 radius-4">Pending</div>';
                } else {
                    return '<div class="status-btn status-btn-orange font-13 radius-4">Cancelled</div>';
                }
            })
            ->addColumn('action', function ($order) {
                $html = '<div class="tbl-action-btns d-inline-flex">';
                if ($order->payment_status == ORDER_PAYMENT_STATUS_PENDING) {
                    $html .= '<button type="button" class="p-1 tbl-action-btn view" data-id="' . $order->id . '" title="View"><span class="iconify" data-icon="carbon:view-filled"></span></button>';
                    if ($order->gatewaySlug == 'bank') {
                        $html .= '<a href="' . getFileUrl($order->folder_name, $order->file_name) . '"  class="p-1 tbl-action-btn" title="' . __('Bank slip download') . '" download><span class="iconify" data-icon="fa6-solid:download"></span></a>';
                        $html .= '<button type="button" class="p-1 tbl-action-btn orderPayStatus" data-id="' . $order->id . '" title="' . __('Payment Status Change') . '"><span class="iconify" data-icon="fluent:text-change-previous-20-filled"></span></button>';
                    }
                } elseif ($order->payment_status == ORDER_PAYMENT_STATUS_PAID) {
                    $html .= '<button type="button" class="p-1 tbl-action-btn view" data-id="' . $order->id . '" title="' . __('View') . '"><span class="iconify" data-icon="carbon:view-filled"></span></button>';
                }
                $html .= '</div>';
                return $html;
            })
            ->rawColumns(['package', 'name', 'status', 'gateway', 'action'])
            ->make(true);
    }

    public function getByStatus($request)
    {
        $status = 0;
        if ($request->status == 'paid') {
            $status = ORDER_PAYMENT_STATUS_PAID;
        } else if ($request->status == 'pending') {
            $status = ORDER_PAYMENT_STATUS_PENDING;
        } else if ($request->status == 'bank') {
            $status = ORDER_PAYMENT_STATUS_PENDING;
        } else if ($request->status == 'cancelled') {
            $status = ORDER_PAYMENT_STATUS_CANCELLED;
        }
        $orders = SubscriptionOrder::query()
            ->join('packages', 'subscription_orders.package_id', '=', 'packages.id')
            ->join('gateways', 'subscription_orders.gateway_id', '=', 'gateways.id')
            ->join('users', 'subscription_orders.user_id', '=', 'users.id')
            ->whereNull('users.deleted_at')
            ->leftJoin('file_managers', ['subscription_orders.deposit_slip_id' => 'file_managers.id', 'file_managers.origin_type' => (DB::raw("'App\\\Models\\\Order'"))])
            ->orderByDesc('subscription_orders.id');

        if ($request->status == 'bank') {
            $orders->whereNotNull('subscription_orders.deposit_slip_id');
        }
        $orders = $orders->select(['subscription_orders.*', 'packages.name as packageName', 'gateways.title as gatewayTitle', 'gateways.slug as gatewaySlug', 'file_managers.file_name', 'file_managers.folder_name', 'users.last_name', 'users.email'])
            ->where('subscription_orders.payment_status', $status);

        return datatables($orders)
            ->addIndexColumn()
            ->addColumn('package', function ($order) {
                return '<h6>' . $order->packageName . '</h6>';
            })
            ->addColumn('name', function ($order) {
                return $order->first_name . ' ' . $order->last_name . '(' . $order->email . ')';
            })
            ->addColumn('date', function ($order) {
                return $order->created_at->format('Y-m-d h:i');
            })
            ->addColumn('amount', function ($order) {
                return currencyPrice($order->total);
            })
            ->addColumn('gateway', function ($order) {
                if ($order->gatewaySlug == 'bank') {
                    return '<a href="' . getFileUrl($order->folder_name, $order->file_name) . '" title="' . __('Bank slip download') . '" download>' . $order->gatewayTitle . '</a>';
                }
                return $order->gatewayTitle;
            })
            ->addColumn('status', function ($order) {
                if ($order->payment_status == ORDER_PAYMENT_STATUS_PAID) {
                    return '<div class="status-btn status-btn-blue font-13 radius-4">Paid</div>';
                } elseif ($order->payment_status == ORDER_PAYMENT_STATUS_PENDING) {
                    return '<div class="status-btn status-btn-red font-13 radius-4">Pending</div>';
                } else {
                    return '<div class="status-btn status-btn-orange font-13 radius-4">Cancelled</div>';
                }
            })
            ->addColumn('action', function ($order) {
                $html = '<div class="tbl-action-btns d-inline-flex">';
                if ($order->payment_status == ORDER_PAYMENT_STATUS_PENDING) {
                    $html .= '<button type="button" class="p-1 tbl-action-btn view" data-id="' . $order->id . '" title="' . __('View') . '"><span class="iconify" data-icon="carbon:view-filled"></span></button>';
                    if ($order->gatewaySlug == 'bank') {
                        $html .= '<a href="' . getFileUrl($order->folder_name, $order->file_name) . '"  class="p-1 tbl-action-btn" title="' . __('Bank slip download') . '" download><span class="iconify" data-icon="fa6-solid:download"></span></a>';
                        $html .= '<button type="button" class="p-1 tbl-action-btn orderPayStatus" data-id="' . $order->id . '" title="' . __('Payment Status Change') . '"><span class="iconify" data-icon="fluent:text-change-previous-20-filled"></span></button>';
                    } elseif ($order->gatewaySlug == 'cash') {
                        $html .= '<button type="button" class="p-1 tbl-action-btn orderPayStatus" data-id="' . $order->id . '" title="' . __('Payment Status Change') . '"><span class="iconify" data-icon="fluent:text-change-previous-20-filled"></span></button>';
                    }
                } elseif ($order->payment_status == ORDER_PAYMENT_STATUS_PAID) {
                    $html .= '<button type="button" class="p-1 tbl-action-btn view" data-id="' . $order->id . '" title="' . __('View') . '"><span class="iconify" data-icon="carbon:view-filled"></span></button>';
                }
                $html .= '</div>';
                return $html;
            })
            ->rawColumns(['package', 'name', 'status', 'gateway', 'action'])
            ->make(true);
    }

    public function orderGetInfo($id)
    {
        try {
            return SubscriptionOrder::query()
                ->join('gateways', 'subscription_orders.gateway_id', '=', 'gateways.id')
                ->select(['subscription_orders.*', 'gateways.title as gatewayTitle'])
                ->where('subscription_orders.id', $id)
                ->first();
        } catch (Exception $e) {
            $message = getErrorMessage($e, $e->getMessage());
            return $this->error([], $message);
        }
    }

    public function orderPaymentStatusChange($request)
    {
        DB::beginTransaction();
        try {
            $order = SubscriptionOrder::findOrFail($request->id);
            if ($request->status == ORDER_PAYMENT_STATUS_PAID) {
                $order->payment_status = ORDER_PAYMENT_STATUS_PAID;
                $order->transaction_id = str_replace("-", "", uuid_create(UUID_TYPE_RANDOM));
                $duration = 0;
                if ($order->duration_type == PACKAGE_DURATION_TYPE_MONTHLY) {
                    $duration = 30;
                } elseif ($order->duration_type == PACKAGE_DURATION_TYPE_YEARLY) {
                    $duration = 365;
                }
                $package = Package::find($order->package_id);
                setUserPackage($order->user_id, $package, $duration, $order->quantity, $order->id);
            } elseif ($request->status == ORDER_PAYMENT_STATUS_CANCELLED) {
                $order->payment_status = ORDER_PAYMENT_STATUS_CANCELLED;
            } else {
                $order->payment_status = ORDER_PAYMENT_STATUS_PENDING;
            }
            $order->save();
            DB::commit();
            $title = __("You have a new invoice");
            $body = __("Package Assign Successfully");
            addNotification($title, $body, null, null, $order->user_id, auth()->id());
            $message = __(UPDATED_SUCCESSFULLY);
            return $this->success([], $message);
        } catch (Exception $e) {
            DB::rollBack();
            $message = getErrorMessage($e, $e->getMessage());
            return $this->error([], $message);
        }
    }

    public function getAllUserPackageByUser($userId = null)
    {
        $userId = !is_null($userId) ? $userId : auth()->id();
        $orders = OwnerPackage::query()
            ->join('packages', 'owner_packages.package_id', '=', 'packages.id')
            ->join('orders', 'subscription_orders.id', '=', 'owner_packages.order_id')
            ->where('owner_packages.user_id', $userId)
            ->select(['owner_packages.*', 'packages.name as packageName', 'subscription_orders.total'])
            ->get();
        return $this->success($orders);
    }

    public function getPendingOrderByUser($userId = null)
    {
        $userId = !is_null($userId) ? $userId : auth()->id();
        return SubscriptionOrder::query()
            ->join('packages', 'subscription_orders.package_id', '=', 'packages.id')
            ->join('gateways', 'subscription_orders.gateway_id', '=', 'gateways.id')
            ->join('users', 'subscription_orders.user_id', '=', 'users.id')
            ->whereNull('users.deleted_at')
            ->leftJoin('file_managers', ['subscription_orders.deposit_slip_id' => 'file_managers.id', 'file_managers.origin_type' => (DB::raw("'App\\\Models\\\Order'"))])
            ->select(['subscription_orders.*', 'packages.name as packageName', 'gateways.title as gatewayTitle', 'gateways.slug as gatewaySlug', 'file_managers.file_name', 'file_managers.folder_name'])
            ->where('subscription_orders.user_id', $userId)
            ->get();
    }
}
