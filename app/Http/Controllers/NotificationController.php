<?php

namespace App\Http\Controllers;

use App\Services\NotificationService;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public $notificationService;

    public function __construct()
    {
        $this->notificationService = new NotificationService;
    }
    public function status($id)
    {
        $data = $this->notificationService->status($id);
        if ($data->getData()->status == true) {
            return redirect()->back();
        } else {
            return redirect()->back()->with('error', __(SOMETHING_WENT_WRONG));
        }
    }
}
