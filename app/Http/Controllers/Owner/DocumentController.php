<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Http\Requests\DocumentRejectRequest;
use App\Services\KycVerificationService;
use App\Services\PropertyService;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
    use ResponseTrait;
    public $kycVerificationService;
    public $propertyService;

    public function __construct()
    {
        $this->kycVerificationService = new KycVerificationService;
        $this->propertyService = new PropertyService;
    }

    public function index(Request $request)
    {
        $data['pageTitle'] = __('Documents');
        $data['properties'] = $this->propertyService->getAll();
        if ($request->ajax()) {
            return $this->kycVerificationService->getAllData();
        }
        return view('owner.document.index', $data);
    }

    public function statusChange($id)
    {
        return $this->kycVerificationService->statusChange($id);
    }

    public function rejectReasonStore(DocumentRejectRequest $request)
    {
        return $this->kycVerificationService->rejectReasonStore($request);
    }

    public function getInfo(Request $request)
    {
        $data = $this->kycVerificationService->getInfo($request->id);
        $data->front = $data->front;
        $data->back = $data->back;
        return $this->success($data);
    }

    public function delete($id)
    {
        return $this->kycVerificationService->deleteItem($id);
    }
}
