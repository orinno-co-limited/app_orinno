<?php

namespace App\Services;

use App\Models\GatewayCurrency;
use App\Models\OwnerPackage;
use App\Models\Package;
use App\Models\User;
use App\Traits\ResponseTrait;

class SubscriptionService
{
    use ResponseTrait;

    public function getCurrentPlan($userId = null)
    {
        $userId = $userId == null ? getOwnerUserId() : $userId;
        $ownerPackage = OwnerPackage::query()
            ->leftJoin('subscription_orders', 'subscription_orders.id', '=', 'owner_packages.order_id')
            ->where('owner_packages.user_id', $userId)
            ->whereIn('owner_packages.status', [ACTIVE])
            ->whereDate('owner_packages.end_date', '>=', now())
            ->select('owner_packages.*', 'subscription_orders.duration_type')
            ->first();

        return $ownerPackage?->makeHidden(['created_at', 'updated_at', 'deleted_at', 'is_trail', 'order_id', 'package_id', 'user_id']);
    }

    public function getAllPackages()
    {
        return Package::where('status', ACTIVE)->where('is_trail', '!=', ACTIVE)->get();
    }

    public function getById($id)
    {
        $package = Package::query()->findOrFail($id);
        return $package?->makeHidden(['created_at', 'deleted_at', 'updated_at']);
    }

    public function getCurrencyByGatewayId($id)
    {
        $userId = User::where('role', USER_ROLE_ADMIN)->first()->id;
        $currencies = GatewayCurrency::where(['owner_user_id' => $userId, 'gateway_id' => $id])->get();
        foreach ($currencies as $currency) {
            $currency->symbol =  $currency->symbol;
        }
        return $currencies?->makeHidden(['created_at', 'updated_at', 'deleted_at', 'gateway_id', 'owner_user_id']);
    }

    public function cancel()
    {
        return OwnerPackage::query()
            ->where(['user_id' => auth()->id(), 'status' => ACTIVE])
            ->whereDate('end_date', '>=', now()->toDateTimeString())
            ->update(['status' => DEACTIVATE]);
    }
}
