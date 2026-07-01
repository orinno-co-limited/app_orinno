<?php

namespace App\Http\Controllers\Api\Owner;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use App\Models\User;
use App\Services\GatewayService;
use App\Services\SubscriptionService;
use App\Traits\ResponseTrait;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    use ResponseTrait;
    public $subscriptionService;

    public function __construct()
    {
        $this->subscriptionService = new SubscriptionService;
    }

    public function index()
    {
        $data['currentPlan'] = $this->subscriptionService->getCurrentPlan();
        return $this->success($data);
    }

    public function getPlan()
    {
        $data['plans'] = $this->subscriptionService->getAllPackages();
        $data['currentPlan'] = $this->subscriptionService->getCurrentPlan();
        return $this->success($data);
    }

    public function order(Request $request)
    {
        try {
            $user = User::where('role', USER_ROLE_ADMIN)->first();
            if (is_null($user)) {
                throw new Exception(__(SOMETHING_WENT_WRONG));
            }
            $gateWayService = new GatewayService;
            $data['gateways'] = $gateWayService->getActiveAll($user->id);
            $data['plan'] = $this->subscriptionService->getById($request->id);
            $data['durationType'] = $request->duration_type;
            $data['banks'] = Bank::where('owner_user_id', $user->id)->select(['name', 'details', 'id'])->where('status', ACTIVE)->get();
            $data['startDate'] = now();
            if ($request->duration_type == PACKAGE_DURATION_TYPE_MONTHLY) {
                $data['endDate'] = Carbon::now()->addMonth();
            } else {
                $data['endDate'] = Carbon::now()->addYear();
            }
            return $this->success($data);
        } catch (Exception $e) {
            return $this->error([],  $e->getMessage());
        }
    }

    public function getCurrencyByGateway(Request $request)
    {
        $data['currencies'] =  $this->subscriptionService->getCurrencyByGatewayId($request->id);
        return $this->success($data);
    }

    public function cancel()
    {
        try {
            $this->subscriptionService->cancel();
            return $this->success([], __(CANCELED_SUCCESSFULLY));
        } catch (Exception $e) {
            return $this->error([], $e->getMessage());
        }
    }
}
