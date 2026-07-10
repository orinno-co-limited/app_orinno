<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\DB;

class MonitoringService
{
    public function getLandlordTenantCounts($request)
    {
        $owners = User::query()
            ->where('users.role', USER_ROLE_OWNER)
            ->whereNull('users.deleted_at')
            ->leftJoin(DB::raw('(select owner_user_id, COUNT(*) as tenant_count from tenants where deleted_at is null group by owner_user_id) as t'), 't.owner_user_id', '=', 'users.id')
            ->leftJoin(DB::raw('(select owner_user_id, COUNT(*) as property_count from properties where deleted_at is null group by owner_user_id) as p'), 'p.owner_user_id', '=', 'users.id')
            ->select([
                'users.id',
                'users.first_name',
                'users.last_name',
                'users.email',
                'users.contact_number',
                'users.status',
                DB::raw('COALESCE(t.tenant_count, 0) as tenant_count'),
                DB::raw('COALESCE(p.property_count, 0) as property_count'),
            ])
            ->orderByDesc('tenant_count');

        return datatables($owners)
            ->addIndexColumn()
            ->addColumn('name', function ($owner) {
                return $owner->first_name . ' ' . $owner->last_name;
            })
            ->addColumn('status', function ($owner) {
                if ($owner->status == USER_STATUS_ACTIVE) {
                    return '<div class="status-btn status-btn-green font-13 radius-4">' . __('Active') . '</div>';
                } elseif ($owner->status == USER_STATUS_INACTIVE) {
                    return '<div class="status-btn status-btn-purple font-13 radius-4">' . __('Inactive') . '</div>';
                } elseif ($owner->status == USER_STATUS_DELETED) {
                    return '<div class="status-btn status-btn-red font-13 radius-4">' . __('Deleted') . '</div>';
                } else {
                    return '<div class="status-btn status-btn-orange font-13 radius-4">' . __('Unverified') . '</div>';
                }
            })
            ->rawColumns(['name', 'status'])
            ->make(true);
    }

    public function getAllTenants($request)
    {
        $tenants = DB::table('tenants')
            ->join('users', 'tenants.user_id', '=', 'users.id')
            ->leftJoin('properties', 'tenants.property_id', '=', 'properties.id')
            ->leftJoin('property_units', 'tenants.unit_id', '=', 'property_units.id')
            ->leftJoin('users as owners', 'tenants.owner_user_id', '=', 'owners.id')
            ->whereNull('tenants.deleted_at')
            ->whereNull('users.deleted_at')
            ->select([
                'tenants.id',
                'tenants.status',
                'users.first_name',
                'users.last_name',
                'users.email',
                'users.contact_number',
                'users.status as user_status',
                'properties.name as property_name',
                'property_units.unit_name',
                DB::raw("CONCAT(owners.first_name, ' ', owners.last_name) as landlord_name"),
            ])
            ->orderByDesc('tenants.id');

        return datatables($tenants)
            ->addIndexColumn()
            ->addColumn('name', function ($tenant) {
                return $tenant->first_name . ' ' . $tenant->last_name;
            })
            ->addColumn('landlord', function ($tenant) {
                return $tenant->landlord_name ?: 'N/A';
            })
            ->addColumn('property', function ($tenant) {
                return $tenant->property_name ?: 'N/A';
            })
            ->addColumn('unit', function ($tenant) {
                return $tenant->unit_name ?: 'N/A';
            })
            ->addColumn('status', function ($tenant) {
                if ($tenant->user_status == USER_STATUS_DELETED) {
                    return '<div class="status-btn status-btn-red font-13 radius-4">' . __('Deleted') . '</div>';
                } elseif ($tenant->status == TENANT_STATUS_ACTIVE) {
                    return '<div class="status-btn status-btn-green font-13 radius-4">' . __('Active') . '</div>';
                } elseif ($tenant->status == TENANT_STATUS_INACTIVE) {
                    return '<div class="status-btn status-btn-orange font-13 radius-4">' . __('Inactive') . '</div>';
                } elseif ($tenant->status == TENANT_STATUS_CLOSE) {
                    return '<div class="status-btn status-btn-purple font-13 radius-4">' . __('Close') . '</div>';
                } else {
                    return '<div class="status-btn status-btn-blue font-13 radius-4">' . __('Draft') . '</div>';
                }
            })
            ->rawColumns(['status'])
            ->make(true);
    }
}
