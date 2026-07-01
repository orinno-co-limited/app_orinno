<?php

namespace App\Services;

use App\Models\InvoiceRecurringSetting;
use App\Models\InvoiceRecurringSettingItem;
use App\Models\Tenant;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Support\Facades\DB;

class InvoiceRecurringService
{
    use ResponseTrait;

    public function getAllData()
    {
        $invoiceRecurring = InvoiceRecurringSetting::query()
            ->where('invoice_recurring_settings.owner_user_id', getOwnerUserId())
            ->join('properties', 'invoice_recurring_settings.property_id', '=', 'properties.id')
            ->join('property_units', 'invoice_recurring_settings.property_unit_id', '=', 'property_units.id')
            ->select(['invoice_recurring_settings.*', 'properties.name as propertyName', 'property_units.unit_name']);

        return datatables($invoiceRecurring)
            ->addColumn('prefix', function ($invoiceRecurring) {
                return '<h6>' . $invoiceRecurring->invoice_prefix . '</h6>';
            })
            ->addColumn('property', function ($invoiceRecurring) {
                return '<h6>' . @$invoiceRecurring->propertyName . '</h6>
                        <p class="font-13">' . @$invoiceRecurring->unit_name . '</p>';
            })
            ->addColumn('type', function ($invoiceRecurring) {
                $type = '';
                if ($invoiceRecurring->recurring_type == INVOICE_RECURRING_TYPE_MONTHLY) {
                    $type = '<h6>Monthly</h6>';
                } elseif ($invoiceRecurring->recurring_type == INVOICE_RECURRING_TYPE_YEARLY) {
                    $type = '<h6>Yearly</h6>';
                } elseif ($invoiceRecurring->recurring_type == INVOICE_RECURRING_TYPE_CUSTOM) {
                    $type = '<h6>Custom</h6><p>' . $invoiceRecurring->cycle_day . ' Days</p>';
                }
                return $type;
            })
            ->addColumn('amount', function ($invoiceRecurring) {
                return currencyPrice($invoiceRecurring->amount);
            })
            ->addColumn('status', function ($invoiceRecurring) {
                if ($invoiceRecurring->status == ACTIVE) {
                    return '<div class="status-btn status-btn-blue font-13 radius-4">Active</div>';
                } else {
                    return '<div class="status-btn status-btn-orange font-13 radius-4">Deactivate</div>';
                }
            })
            ->addColumn('action', function ($invoiceRecurring) {
                $html = '<div class="tbl-action-btns d-inline-flex">';
                $html .= '<button type="button" class="p-1 tbl-action-btn edit" data-detailsurl="' . route('owner.invoice.recurring-setting.details', $invoiceRecurring->id) . '" title="' . __('Edit') . '"><span class="iconify" data-icon="clarity:note-edit-solid"></span></button>';
                $html .= '<button type="button" class="p-1 tbl-action-btn view" data-detailsurl="' . route('owner.invoice.recurring-setting.details', $invoiceRecurring->id) . '" title="' . __('View') . '"><span class="iconify" data-icon="carbon:view-filled"></span></button>';
                $html .= '<button type="button" onclick="deleteItem(\'' . route('owner.invoice.recurring-setting.destroy', $invoiceRecurring->id) . '\', \'allInvoiceDatatable\')" class="p-1 tbl-action-btn" title="' . __('Delete') . '"><span class="iconify" data-icon="ep:delete-filled"></span></button>';
                $html .= '</div>';
                return $html;
            })
            ->rawColumns(['prefix', 'property', 'type', 'status', 'action'])
            ->make(true);
    }

    public function getById($id)
    {
        return InvoiceRecurringSetting::where('owner_user_id', getOwnerUserId())->findOrFail($id);
    }

    public function getItemsByInvoiceRecurringId($id)
    {
        return InvoiceRecurringSettingItem::where('invoice_recurring_setting_id', $id)->get();
    }

    public function store($request)
    {
        DB::beginTransaction();
        try {
            $tenant = Tenant::where('unit_id', $request->property_unit_id)->where('status', TENANT_STATUS_ACTIVE)->first();
            if (!$tenant) {
                throw new Exception(__('Tenant Not Found'));
            }
            $id = $request->get('id', '');
            if ($id != '') {
                $invoiceRecurring = InvoiceRecurringSetting::where('owner_user_id', getOwnerUserId())->findOrFail($request->id);
            } else {
                if (!getOwnerLimit(RULES_AUTO_INVOICE) > 0) {
                    throw new Exception(__('Your Auto Invoice Settings Limit Finished'));
                }
                $invoiceRecurring = new InvoiceRecurringSetting();
            }
            $invoiceRecurring->invoice_prefix = $request->invoice_prefix;
            $invoiceRecurring->tenant_id = $tenant->id;
            $invoiceRecurring->owner_user_id = getOwnerUserId();
            $invoiceRecurring->property_id = $request->property_id;
            $invoiceRecurring->property_unit_id = $request->property_unit_id;
            $invoiceRecurring->start_date = $request->start_date ?? now();
            $invoiceRecurring->recurring_type = $request->recurring_type;
            $invoiceRecurring->cycle_day = $request->cycle_day;
            $invoiceRecurring->due_day_after = $request->due_day_after;
            $invoiceRecurring->status = $request->status;
            $invoiceRecurring->save();
            $totalAmount = 0;
            $now = now();
            if (!is_null($request->invoiceItem)) {
                if (count($request->invoiceItem['invoice_type_id']) > 0) {
                    for ($i = 0; $i < count($request->invoiceItem['invoice_type_id']); $i++) {
                        if ($request->invoiceItem['id'][$i]) {
                            $invoiceRecurringItem = InvoiceRecurringSettingItem::findOrFail($request->invoiceItem['id'][$i]);
                        } else {
                            $invoiceRecurringItem = new InvoiceRecurringSettingItem();
                        }
                        $invoiceRecurringItem->invoice_recurring_setting_id = $invoiceRecurring->id;
                        $invoiceRecurringItem->invoice_type_id = $request->invoiceItem['invoice_type_id'][$i];
                        $invoiceRecurringItem->amount = $request->invoiceItem['amount'][$i];
                        $invoiceRecurringItem->description = $request->invoiceItem['description'][$i];
                        $invoiceRecurringItem->updated_at = $now;
                        $invoiceRecurringItem->save();
                        $totalAmount += $invoiceRecurringItem->amount;
                    }
                    InvoiceRecurringSettingItem::where('invoice_recurring_setting_id', $invoiceRecurring->id)->where('updated_at', '!=', $now)->get()->map(function ($q) {
                        $q->delete();
                    });
                }
            } else {
                throw new Exception('No Item Add');
            }
            $invoiceRecurring->amount = $totalAmount;
            $invoiceRecurring->save();
            DB::commit();
            $message = $request->id ? __(UPDATED_SUCCESSFULLY) : __(CREATED_SUCCESSFULLY);
            return $this->success([], $message);
        } catch (\Exception $e) {
            DB::rollBack();
            $message = getErrorMessage($e, $e->getMessage());
            return $this->error([],  $message);
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $invoice = InvoiceRecurringSetting::where('owner_user_id', getOwnerUserId())->findOrFail($id);
            $invoice->delete();
            DB::commit();
            $message = __(DELETED_SUCCESSFULLY);
            return $this->success([], $message);
        } catch (\Exception $e) {
            DB::rollBack();
            $message = getErrorMessage($e, $e->getMessage());
            return $this->error([],  $message);
        }
    }
}
