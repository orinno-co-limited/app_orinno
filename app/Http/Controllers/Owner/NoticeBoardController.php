<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Http\Requests\NoticeBoardRequest;
use App\Services\NoticeBoardService;
use App\Services\PropertyService;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;

class NoticeBoardController extends Controller
{
    use ResponseTrait;
    public $noticeboardService;
    public $propertyService;

    public function __construct()
    {
        $this->noticeboardService = new NoticeBoardService;
        $this->propertyService = new PropertyService;
    }

    public function index(Request $request)
    {
        if (isAddonInstalled('PROTYSAAS') > 1) {
            if (ownerCurrentPackage(getOwnerUserId())?->ticket_support != ACTIVE) {
                return abort(403);
            }
        }
        $data['pageTitle'] = __('Notice Board');
        $data['properties'] = $this->propertyService->getAll();
        if ($request->ajax()) {
            return $this->noticeboardService->getAllData();
        }
        return view('owner.noticeboards.index', $data);
    }

    public function store(NoticeBoardRequest $request)
    {
        return $this->noticeboardService->store($request);
    }

    public function getInfo(Request $request)
    {
        $data = $this->noticeboardService->getInfo($request->id);
        return $this->success($data);
    }

    public function delete($id)
    {
        return $this->noticeboardService->deleteById($id);
    }
}
