<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\Package;
use App\Models\Property;
use App\Models\PropertyUnit;
use App\Models\SubscriptionOrder;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $data['pageTitle'] = __('Dashboard');
        $data['totalOwner'] = User::where('role', USER_ROLE_OWNER)->count();
        $data['totalProperty'] = Property::count();
        $data['totalUnit'] = PropertyUnit::count();
        $data['totalTenant'] = Tenant::count();
        $data['packages'] = Package::limit(10)->get();

        $data['orders'] =  SubscriptionOrder::query()
            ->leftJoin('packages', 'subscription_orders.package_id', '=', 'packages.id')
            ->leftJoin('gateways', 'subscription_orders.gateway_id', '=', 'gateways.id')
            ->leftJoin('users', 'subscription_orders.user_id', '=', 'users.id')
            ->select(['subscription_orders.*', 'packages.name as packageName', 'gateways.title as gatewayTitle', 'gateways.slug as gatewaySlug'])
            ->limit(10)
            ->orderByDesc('subscription_orders.id')
            ->get();

        return view('admin.dashboard')->with($data);
    }

    public function notification()
    {
        $data['pageTitle'] = __('Notification');
        Notification::query()
            ->where(function ($q) {
                $q->where('notifications.user_id', auth()->id())
                    ->orWhere('notifications.user_id', null);
            })
            ->update(['is_seen' => ACTIVE]);
        return view('admin.notification')->with($data);
    }
}
