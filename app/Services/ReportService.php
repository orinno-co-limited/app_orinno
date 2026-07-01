<?php

namespace App\Services;

use App\Http\Requests\MaintainerRequest;
use App\Models\Expense;
use App\Models\Invoice;
use App\Models\MaintenanceRequest;
use App\Models\Property;
use App\Models\Tenant;
use Illuminate\Support\Facades\DB;

class ReportService
{
    public function earning()
    {
        $request = request()->all();

        $invoice = Invoice::query()
            ->join('properties', 'invoices.property_id', '=', 'properties.id')
            ->join('property_units', 'invoices.property_unit_id', '=', 'property_units.id')
            ->select('invoices.name', 'invoices.invoice_no', 'invoices.amount', 'invoices.tax_amount', 'invoices.created_at', 'properties.name as property_name', 'property_units.unit_name')
            ->where('invoices.owner_user_id', getOwnerUserId())
            ->where('invoices.status', INVOICE_STATUS_PAID);

        if ($request['property_id'] != null) {
            $invoice->where('invoices.property_id', $request['property_id']);
        }

        if ($request['unit_id'] != null) {
            $invoice->where('invoices.property_unit_id', $request['unit_id']);
        }

        if ($request['start_date'] != null && $request['end_date'] != null) {
            $startDate = date('Y-m-d H:i:s', strtotime($request['start_date']));
            $endDate = date('Y-m-d H:i:s', strtotime($request['end_date'] . ' 23:59:59'));
            $invoice->whereBetween('invoices.created_at', [$startDate, $endDate]);
        }

        return datatables($invoice)
            ->addIndexColumn()
            ->addColumn('invoice', function ($invoice) {
                return  $invoice->invoice_no;
            })
            ->addColumn('property', function ($invoice) {
                return $invoice->property_name;
            })
            ->addColumn('unit', function ($invoice) {
                return $invoice->unit_name;
            })
            ->addColumn('date', function ($item) {
                return $item->created_at->format('Y-m-d');
            })
            ->addColumn('tax_amount', function ($invoice) {
                return currencyPrice($invoice->tax_amount);
            })
            ->addColumn('amount', function ($invoice) {
                return currencyPrice($invoice->amount);
            })
            ->with('total', $invoice->sum('amount'))
            ->with('tax_amount', $invoice->sum('tax_amount'))
            ->rawColumns(['invoice', 'property', 'unit', 'date', 'tax_amount', 'amount'])
            ->make(true);
    }

    public function lossProfitByMonth()
    {
        $invoices = Invoice::query()
            ->select(
                DB::raw('sum(invoices.amount) as `income`'),
                DB::raw("DATE_FORMAT(invoices.due_date,'%Y-%m') as month"),
            )
            ->groupBy(DB::raw('YEAR(invoices.due_date),MONTH(invoices.due_date)'))
            ->where('invoices.status', INVOICE_STATUS_PAID)
            ->get();

        $maxInvDate = '2000-12';
        $minInvDate = '2034-01';
        foreach ($invoices as $invoice) {
            if ($invoice->month > $maxInvDate) {
                $maxInvDate = $invoice->month;
            }
            if ($invoice->month < $minInvDate) {
                $minInvDate = $invoice->month;
            }
        }
        $expenses = Expense::query()
            ->select(
                DB::raw('sum(total_amount) as `expense`'),
                DB::raw("DATE_FORMAT(created_at,'%Y-%m') as month"),
            )
            ->groupBy(DB::raw('YEAR(created_at),MONTH(created_at)'))
            ->get();

        foreach ($expenses as $expense) {
            if ($expense->month > $maxInvDate) {
                $maxInvDate = $expense->month;
            }
            if ($expense->month < $minInvDate) {
                $minInvDate = $expense->month;
            }
        }

        $data = [];
        $currentMonth = $minInvDate;
        while (1) {
            if ($currentMonth > $maxInvDate) {
                break;
            }
            $data[$currentMonth] = [
                'income' => 0,
                'expense' => 0,
                'month' => $currentMonth,
            ];
            $currentMonth = date('Y-m', strtotime("+1 months", strtotime($currentMonth)));
        }
        foreach ($invoices as $invoice) {
            $data[$invoice->month]['income'] = $invoice->income;
        }
        foreach ($expenses as $invoice) {
            $data[$invoice->month]['expense'] = $invoice->expense;
        }
        return $data;
    }

    public function expenses()
    {
        $request = request()->all();
        $expenses = Expense::query()
            ->join('properties', 'expenses.property_id', '=', 'properties.id')
            ->join('property_units', 'expenses.property_unit_id', '=', 'property_units.id')
            ->where('expenses.owner_user_id', getOwnerUserId())
            ->select('expenses.name',  'expenses.total_amount', 'expenses.created_at', 'properties.name as property_name', 'property_units.unit_name');

        if ($request['property_id'] != null) {
            $expenses->where('expenses.property_id', $request['property_id']);
        }

        if ($request['unit_id'] != null) {
            $expenses->where('expenses.property_unit_id', $request['unit_id']);
        }

        if ($request['start_date'] != null && $request['end_date'] != null) {
            $startDate = date('Y-m-d H:i:s', strtotime($request['start_date']));
            $endDate = date('Y-m-d H:i:s', strtotime($request['end_date'] . ' 23:59:59'));
            $expenses->whereBetween('expenses.created_at', [$startDate, $endDate]);
        }

        return datatables($expenses)
            ->addIndexColumn()
            ->addColumn('name', function ($expense) {
                return  $expense->name;
            })
            ->addColumn('property', function ($expense) {
                return $expense->property_name;
            })
            ->addColumn('unit', function ($expense) {
                return $expense->unit_name;
            })
            ->addColumn('date', function ($item) {
                return $item->created_at->format('Y-m-d');
            })
            ->addColumn('amount', function ($expense) {
                return currencyPrice($expense->total_amount);
            })
            ->with('total', $expenses->sum('total_amount'))
            ->rawColumns(['name', 'property', 'unit', 'date', 'amount'])
            ->make(true);
    }

    public function leases()
    {
        $request = request()->all();
        $tenants = Tenant::query()
            ->join('users', 'tenants.user_id', '=', 'users.id')
            ->join('properties', 'tenants.property_id', '=', 'properties.id')
            ->join('property_units', 'tenants.unit_id', '=', 'property_units.id')
            ->whereNull('users.deleted_at')
            ->where('tenants.owner_user_id', getOwnerUserId())
            ->select('tenants.*', 'users.first_name', 'users.last_name', 'properties.name as property_name', 'property_units.unit_name');


        return datatables($tenants)
            ->addIndexColumn()
            ->addColumn('name', function ($tenant) {
                return  $tenant->first_name . ' ' . $tenant->last_name;
            })
            ->addColumn('property', function ($tenant) {
                return $tenant->property_name;
            })
            ->addColumn('unit', function ($tenant) {
                return $tenant->unit_name;
            })
            ->addColumn('start_date', function ($item) {
                return $item->lease_start_date;
            })
            ->addColumn('end_date', function ($item) {
                return $item->lease_end_date;
            })
            ->rawColumns(['name'])
            ->make(true);
    }

    public function occupancy()
    {
        $properties = Property::query()
            ->leftJoin('tenants', ['properties.id' => 'tenants.property_id', 'tenants.status' => (DB::raw(TENANT_STATUS_ACTIVE))])
            ->selectRaw('properties.number_of_unit - (COUNT(tenants.id)) as available_unit,properties.*')
            ->groupBy('properties.id')
            ->where('properties.owner_user_id', getOwnerUserId());

        return datatables($properties)
            ->addIndexColumn()
            ->addColumn('property_name', function ($property) {
                return  $property->name;
            })
            ->addColumn('address', function ($property) {
                return  $property->propertyDetail?->address;
            })
            ->addColumn('unit', function ($property) {
                return  $property->number_of_unit;
            })
            ->addColumn('available', function ($property) {
                return $property->available_unit;
            })
            ->addColumn('turn_over', function ($property) {
                $turnOver = ($property->available_unit / $property->number_of_unit) * 100;
                return $turnOver . '%';
            })
            ->rawColumns(['property_name', 'turn_over'])
            ->make(true);
    }

    public function maintenance()
    {
        $maintenance = MaintenanceRequest::query()
            ->join('properties', 'maintenance_requests.property_id', '=', 'properties.id')
            ->join('property_units', 'maintenance_requests.unit_id', '=', 'property_units.id')
            ->join('tenants', 'maintenance_requests.unit_id', '=', 'tenants.unit_id')
            ->join('users', 'tenants.user_id', '=', 'users.id')
            ->join('maintenance_issues', 'maintenance_requests.issue_id', '=', 'maintenance_issues.id')
            ->whereNull('users.deleted_at')
            ->where('maintenance_requests.owner_user_id', getOwnerUserId())
            ->select('maintenance_requests.*', 'properties.name as property_name', 'maintenance_issues.name as issue_name', 'property_units.unit_name', 'users.first_name', 'users.last_name');
        return datatables($maintenance)
            ->addIndexColumn()
            ->addColumn('tenant_name', function ($maintenance) {
                return $maintenance->first_name . ' ' . $maintenance->last_name;
            })
            ->addColumn('status', function ($maintenance) {
                if ($maintenance->status == MAINTENANCE_REQUEST_STATUS_COMPLETE) {
                    return '<div class="status-btn status-btn-green font-13 radius-4">' . __('Completed') . '</div>';
                } elseif ($maintenance->status == MAINTENANCE_REQUEST_STATUS_INPROGRESS) {
                    return '<div class="status-btn status-btn-orange font-13 radius-4">' . __('In Progress') . '</div>';
                } else {
                    return '<div class="status-btn status-btn-red font-13 radius-4">' . __('Pending') . '</div>';
                }
            })
            ->rawColumns(['status'])
            ->make(true);
    }

    public function tenant()
    {
        $tenants = Tenant::query()
            ->join('users', 'tenants.user_id', '=', 'users.id')
            ->whereNull('users.deleted_at')
            ->leftJoin('properties', 'tenants.property_id', '=', 'properties.id')
            ->leftJoin('property_units', 'tenants.unit_id', '=', 'property_units.id')
            ->leftJoin(DB::raw('(select tenant_id, SUM(amount) as paid from invoices where status = 1 group By tenant_id) as inv_paid'), ['inv_paid.tenant_id' => 'tenants.id'])
            ->leftJoin(DB::raw('(select tenant_id, SUM(amount) as due from invoices where status = 0 group By tenant_id) as inv_due'), ['inv_due.tenant_id' => 'tenants.id'])
            ->select(['tenants.*', 'inv_paid.paid', 'inv_due.due', 'users.first_name', 'users.last_name', 'users.contact_number', 'users.email', 'property_units.unit_name', 'properties.name as property_name'])
            ->where('tenants.owner_user_id', getOwnerUserId());

        return datatables($tenants)
            ->addIndexColumn()
            ->addColumn('name', function ($tenant) {
                return $tenant->first_name . ' ' . $tenant->last_name;
            })
            ->addColumn('email', function ($tenant) {
                return $tenant->email;
            })
            ->addColumn('contact', function ($tenant) {
                return $tenant->contact_number;
            })
            ->addColumn('property', function ($tenant) {
                return $tenant->property_name;
            })
            ->addColumn('unit', function ($tenant) {
                return $tenant->unit_name;
            })
            ->addColumn('paid', function ($tenant) {
                return currencyPrice($tenant->paid);
            })
            ->addColumn('due', function ($tenant) {
                return currencyPrice($tenant->due);
            })
            ->addColumn('status', function ($tenant) {
                $html = '';
                if ($tenant->status == TENANT_STATUS_ACTIVE) {
                    $html = ' <div class="status-btn status-btn-green font-13 radius-4">' . __('Active') . '</div>';
                } elseif ($tenant->status == TENANT_STATUS_INACTIVE) {
                    $html = ' <div class="status-btn status-btn-orange font-13 radius-4">' . __('Inactive') . '</div>';
                } elseif ($tenant->status == TENANT_STATUS_DRAFT) {
                    $html = ' <div class="status-btn status-btn-blue font-13 radius-4">' . __('Draft') . '</div>';
                } elseif ($tenant->status == TENANT_STATUS_CLOSE) {
                    $html = ' <div class="status-btn status-btn-red font-13 radius-4">' . __('Close') . '</div>';
                }
                return $html;
            })

            ->rawColumns(['name', 'property', 'status'])
            ->make(true);
    }
}
