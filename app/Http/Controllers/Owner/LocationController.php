<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Services\LocationService;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    use ResponseTrait;
    public $locationService;

    public function __construct()
    {
        $this->locationService = new LocationService;
    }
    public function countryList()
    {
        return $this->locationService->getCountry();
    }

    public function stateList(Request $request)
    {
        return $this->locationService->getStateByCountryId($request->country_id);
    }

    public function cityList(Request $request)
    {
        return $this->locationService->getCitiesByStateId($request->state_id);
    }
}
