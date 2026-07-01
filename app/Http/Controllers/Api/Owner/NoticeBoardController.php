<?php

namespace App\Http\Controllers\Api\Owner;

use App\Http\Controllers\Controller;
use App\Http\Requests\NoticeBoardRequest;
use App\Services\NoticeBoardService;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;

class NoticeBoardController extends Controller
{
    use ResponseTrait;
    public $noticeboardService;

    public function __construct()
    {
        $this->noticeboardService = new NoticeBoardService;
    }

    public function index()
    {
        if (isAddonInstalled('PROTYSAAS') > 1) {
            if (ownerCurrentPackage(auth()->id())?->ticket_support != ACTIVE) {
                return abort(403);
            }
        }
        $data['noticeBoards'] = $this->noticeboardService->getAll();
        return $this->success($data);
    }

    public function store(NoticeBoardRequest $request)
    {
        return $this->noticeboardService->store($request);
    }

    public function getInfo($id)
    {
        $data = $this->noticeboardService->getInfo($id);
        return $this->success($data);
    }

    public function delete($id)
    {
        return $this->noticeboardService->deleteById($id);
    }
}
