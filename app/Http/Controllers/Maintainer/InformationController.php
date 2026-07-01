<?php

namespace App\Http\Controllers\Maintainer;

use App\Http\Controllers\Controller;
use App\Services\InformationService;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;

class InformationController extends Controller
{
    use ResponseTrait;

    public $informationService;

    public function __construct()
    {
        $this->informationService = new InformationService;
    }

    public function index()
    {
        $data['pageTitle'] = __('Information');
        $data['information'] = $this->informationService->getByPropertyId(auth()->user()->maintainer->properties->pluck('id')->toArray());
        return view('maintainer.information.index', $data);
    }

    public function getInfo(Request $request)
    {
        $data = $this->informationService->getInfo($request->id);
        $data->image = $data->image;
        return $this->success($data);
    }
}
