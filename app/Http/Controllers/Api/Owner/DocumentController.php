<?php

namespace App\Http\Controllers\Api\Owner;

use App\Http\Controllers\Controller;
use App\Http\Requests\DocumentRejectRequest;
use App\Services\KycVerificationService;
use App\Traits\ResponseTrait;
use Exception;

class DocumentController extends Controller
{
    use ResponseTrait;
    public $kycVerificationService;
    public $propertyService;

    public function __construct()
    {
        $this->kycVerificationService = new KycVerificationService;
    }

    public function index()
    {
        try {
            $data['documents'] = $this->kycVerificationService->getAll();
            return $this->success($data);
        } catch (Exception $e) {
            return $this->error([], $e->getMessage());
        }
    }

    public function statusChange($id)
    {
        return $this->kycVerificationService->statusChange($id);
    }

    public function rejectReasonStore(DocumentRejectRequest $request)
    {
        return $this->kycVerificationService->rejectReasonStore($request);
    }

    public function getInfo($id)
    {
        $data = $this->kycVerificationService->getInfo($id);
        $data->front = $data->front;
        $data->back = $data->back;
        $data?->makeHidden(['created_at', 'updated_at', 'deleted_at', 'front_id', 'back_id']);
        return $this->success($data);
    }

    public function delete($id)
    {
        return $this->kycVerificationService->deleteItem($id);
    }
}
