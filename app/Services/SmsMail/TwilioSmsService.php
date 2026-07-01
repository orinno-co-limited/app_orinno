<?php

namespace App\Services\SmsMail;

use App\Models\SmsHistory;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Support\Facades\Log;
use Twilio\Rest\Client;

class TwilioSmsService
{
    use ResponseTrait;

    public function getAllDataByOwnerId()
    {
        $histories = SmsHistory::query()->where('owner_user_id', getOwnerUserId());

        return datatables($histories)
            ->addIndexColumn()
            ->addColumn('date', function ($history) {
                return date('Y-m-d', strtotime($history->date));
            })
            ->addColumn('message', function ($history) {
                return $history->message;
            })
            ->addColumn('number', function ($history) {
                return $history->phone_number;
            })
            ->addColumn('status', function ($history) {
                if ($history->status == SMS_STATUS_DELIVERED) {
                    return '<div class="status-btn status-btn-blue font-13 radius-4">Delivered</div>';
                } else {
                    return '<div class="status-btn status-btn-orange font-13 radius-4" title="' . $history->error . '">Failed</div>';
                }
            })
            ->rawColumns(['date', 'message', 'number', 'status'])
            ->make(true);
    }

    public static function sendSms($numbers = [], $message = null, $ownerUserId = null)
    {
        $sid = getOption('TWILIO_ACCOUNT_SID');
        $token = getOption('TWILIO_AUTH_TOKEN');
        $from_number = getOption('TWILIO_PHONE_NUMBER');

        if (getOption('TWILIO_STATUS', 0) == 1) {
            if (count($numbers)) {
                foreach ($numbers as $key => $number) {
                    try {
                        $client = new Client($sid, $token);
                        $sendStatus = $client->messages->create(
                            $number,
                            [
                                'from' => $from_number,
                                'body' => $message
                            ]
                        );
                        if ($sendStatus->status == 'queued' || $sendStatus->status == 'delivered') {
                            Log::channel('sms-mail')->info('sms status : ' . $sendStatus->status . ', number : ' . $number . ', message : ' . $message . 'key : ' . $key . ', date : ' . date('d-m-Y'));
                            self::historyStore($ownerUserId, $sid, $token, $from_number, $number, $message, SMS_STATUS_DELIVERED);
                        } else {
                            throw new Exception('sms status not delivered');
                        }
                    } catch (Exception $e) {
                        self::historyStore($ownerUserId, $sid, $token, $from_number, $number, $message, SMS_STATUS_FAILED, $e->getMessage());
                        Log::channel('sms-mail')->info($e->getMessage());
                    }
                }
                return 'success';
            } else {
                return __('No number found');
            }
        } else {
            return __('Sms setting not enabled');
        }
    }

    public static function historyStore($ownerUserId, $sid, $token, $from_number, $number, $message, $status, $error = null)
    {
        $history = new SmsHistory();
        $history->owner_user_id = $ownerUserId;
        $history->api = 'sid : ' . $sid . 'token : ' . $token . ' number : ' . $from_number;
        $history->phone_number = $number;
        $history->message = $message;
        $history->status = $status;
        $history->date = now();
        $history->error = $error;
        $history->save();
    }
}
