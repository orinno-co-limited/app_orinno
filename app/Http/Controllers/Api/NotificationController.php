<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\NotificationService;

class NotificationController extends Controller
{
    public $notificationService;

    public function __construct()
    {
        $this->notificationService = new NotificationService;
    }
    public function status($id)
    {
        return $this->notificationService->status($id);
    }
}
