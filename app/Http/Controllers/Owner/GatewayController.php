<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Http\Requests\GatewayRequest;
use App\Services\GatewayService;
use Illuminate\Http\Request;

class GatewayController extends Controller
{
    public $gatewayService;

    public function __construct()
    {
        $this->gatewayService = new GatewayService;
    }

    public function index(Request $request)
    {
        $data['pageTitle'] = __('Gateway');
        $data['subGatewaySettingActiveClass'] = 'active';
        $data['gateways'] = $this->gatewayService->getAll();

        return view('owner.setting.gateway', $data);
    }

    public function store(GatewayRequest $request)
    {
        return $this->gatewayService->store($request);
    }

    public function getInfo(Request $request)
    {
        return $this->gatewayService->getCurrenciesByGatewayId($request->id);
    }

    public function sync(Request $request)
    {
        // Call the syncMissingGateway function
        syncMissingGateway();

        // Redirect back or to a success page
        return redirect()->back()->with('success', 'Gateways synced successfully!');
    }
}
