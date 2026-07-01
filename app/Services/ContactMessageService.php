<?php

namespace App\Services;

use App\Mail\CustomMail;
use App\Models\Message;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class ContactMessageService
{
    use ResponseTrait;
    public function getAllData()
    {
        $messages = Message::query();

        return datatables($messages)
            ->addIndexColumn()
            ->addColumn('name', function ($message) {
                return $message->first_name . ' ' . $message->last_name;
            })
            ->addColumn('email', function ($message) {
                return $message->email;
            })
            ->addColumn('phone', function ($message) {
                return $message->phone;
            })
            ->addColumn('status', function ($message) {
                if ($message->reply != null) {
                    return '<div class="status-btn status-btn-green font-13 radius-4">Replied</div>';
                } elseif ($message->is_view == ACTIVE) {
                    return '<div class="status-btn status-btn-green font-13 radius-4">Viewed</div>';
                } else {
                    return '<div class="status-btn status-btn-orange font-13 radius-4">New</div>';
                }
            })
            ->addColumn('action', function ($message) {
                return '<div class="tbl-action-btns d-inline-flex">
                    <button type="button" class="p-1 tbl-action-btn reply" data-id="' . $message->id . '" title="' . __('reply') . '"><span class="iconify" data-icon="bi:reply"></span></button>
                </div>';
            })
            ->rawColumns(['name', 'status', 'action'])
            ->make(true);
    }

    public function getInfo($id)
    {
        return Message::findOrFail($id);
    }

    public function reply($request)
    {
        DB::beginTransaction();
        try {
            $message = Message::findOrFail($request->id);
            $message->reply = $request->reply;
            $message->save();

            if (env('MAIL_STATUS', 0) == 1 && env('MAIL_USERNAME')) {
                if (filter_var($message->email, FILTER_VALIDATE_EMAIL)) {
                    $details['subject'] = $message->subject;
                    $details['message'] = $request->reply;
                    Mail::to($message->email)->send(new CustomMail($details));
                } else {
                    throw new Exception('Email ' . $message->email . ' is not valid');
                }
            } else {
                throw new Exception('Please setup smtp');
            }
            DB::commit();
            $message = __(REPLIED_SUCCESSFULLY);
            return $this->success([], $message);
        } catch (Exception $e) {
            DB::rollBack();
            $message = getErrorMessage($e, $e->getMessage());
            return $this->error([],  $message);
        }
    }
}
