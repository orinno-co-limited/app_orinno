<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\TestMailRequest;
use App\Mail\CustomMail;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{
    use ResponseTrait;

    public function testSend(TestMailRequest $request)
    {
        try {
            if (env('MAIL_STATUS', 0) == 1 && env('MAIL_USERNAME')) {
                if (filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
                    $details['subject'] = $request->subject;
                    $details['message'] = $request->message;
                    // send mail
                    Mail::to($request->email)->send(new CustomMail($details));
                } else {
                    throw new Exception('Email ' . $request->email . ' is not valid');
                }
            } else {
                throw new Exception('Please setup smtp');
            }
            return $this->success([], __(SENT_SUCCESSFULLY));
        } catch (Exception $e) {
            DB::rollBack();
            $message = getErrorMessage($e, $e->getMessage());
            return $this->error([],  $message);
        }
    }
}
