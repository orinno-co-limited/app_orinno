<?php

namespace App\Services\SmsMail;

use App\Mail\CustomizeMail;
use App\Mail\CustomMail;
use App\Mail\InvoiceMail;
use App\Mail\ReminderMail;
use App\Mail\SignUpMail;
use App\Mail\SubscriptionSuccessMail;
use App\Mail\ThankYouMail;
use App\Mail\UserEmailVerification;
use App\Mail\WelcomeMail;
use App\Models\MailHistory;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class MailService
{
    use ResponseTrait;

    public function getAllDataByOwnerId()
    {
        $histories = MailHistory::query()->where('owner_user_id', getOwnerUserId());

        return datatables($histories)
            ->addIndexColumn()
            ->addColumn('date', function ($history) {
                return date('Y-m-d', strtotime($history->date));
            })
            ->addColumn('message', function ($history) {
                return Str::limit($history->message, 40, '...');
            })
            ->addColumn('email', function ($history) {
                return $history->email;
            })
            ->addColumn('status', function ($history) {
                if ($history->status == MAIL_STATUS_DELIVERED) {
                    return '<div class="status-btn status-btn-blue font-13 radius-4">Delivered</div>';
                } else {
                    return '<div class="status-btn status-btn-orange font-13 radius-4" title="' . $history->error . '">Failed</div>';
                }
            })
            ->rawColumns(['date', 'message', 'email', 'status'])
            ->make(true);
    }

    public static function sendMail($emails = [], $subject = null, $message = null, $ownerUserId = null)
    {
        if (env('MAIL_STATUS', 0) == 1 && env('MAIL_USERNAME')) {
            if (count($emails)) {
                foreach ($emails as $key => $email) {
                    try {
                        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                            $details['subject'] = $subject;
                            $details['message'] = $message;
                            // send mail
                            Mail::to($email)->send(new CustomMail($details));
                            // log generate
                            Log::channel('sms-mail')->info('email : ' . $email . ', subject : ' . $subject . ', message : ' . $message . 'key : ' . $key . ', date : ' . date('d-m-Y'));
                            self::historyStore($ownerUserId, $email, $subject, $message, SMS_STATUS_DELIVERED);
                        } else {
                            throw new Exception('Email ' . $email . ' is not valid');
                        }
                    } catch (Exception $e) {
                        Log::channel('sms-mail')->info($e->getMessage());
                        self::historyStore($ownerUserId, $email, $subject, $message, SMS_STATUS_FAILED, $e->getMessage());
                    }
                }
                return 'success';
            } else {
                return __('No email found');
            }
        } else {
            return __('Smtp setting not enabled');
        }
    }

    public static function sendSignUpMail($emails = [], $subject = null, $message = null, $ownerUserId = null, $password = null)
    {
        if (env('MAIL_STATUS', 0) == 1 && env('MAIL_USERNAME')) {
            if (count($emails)) {
                foreach ($emails as $key => $email) {
                    try {
                        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                            $details['subject'] = $subject;
                            $details['message'] = $message;
                            $details['email'] = $email;
                            $details['password'] = $password;
                            // send mail
                            Mail::to($email)->send(new SignUpMail($details));
                            // log generate
                            Log::channel('sms-mail')->info('email : ' . $email . ', subject : ' . $subject . ', message : ' . $message . 'key : ' . $key . ', date : ' . date('d-m-Y'));
                            self::historyStore($ownerUserId, $email, $subject, $message, SMS_STATUS_DELIVERED);
                        } else {
                            throw new Exception('Email ' . $email . ' is not valid');
                        }
                    } catch (Exception $e) {
                        Log::channel('sms-mail')->info($e->getMessage());
                        self::historyStore($ownerUserId, $email, $subject, $message, SMS_STATUS_FAILED, $e->getMessage());
                    }
                }
                return 'success';
            } else {
                return __('No email found');
            }
        } else {
            return __('Smtp setting not enabled');
        }
    }

    public static function sendWelcomeMail($emails = [], $subject = null, $message = null, $ownerUserId = null)
    {
        if (env('MAIL_STATUS', 0) == 1 && env('MAIL_USERNAME')) {
            if (count($emails)) {
                foreach ($emails as $key => $email) {
                    try {
                        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                            $details['subject'] = $subject;
                            $details['message'] = $message;
                            // send mail
                            Mail::to($email)->send(new WelcomeMail($details));
                            // log generate
                            Log::channel('sms-mail')->info('email : ' . $email . ', subject : ' . $subject . ', message : ' . $message . 'key : ' . $key . ', date : ' . date('d-m-Y'));
                            self::historyStore($ownerUserId, $email, $subject, $message, SMS_STATUS_DELIVERED);
                        } else {
                            throw new Exception('Email ' . $email . ' is not valid');
                        }
                    } catch (Exception $e) {
                        Log::channel('sms-mail')->info($e->getMessage());
                        self::historyStore($ownerUserId, $email, $subject, $message, SMS_STATUS_FAILED, $e->getMessage());
                    }
                }
                return 'success';
            } else {
                return __('No email found');
            }
        } else {
            return __('Smtp setting not enabled');
        }
    }

    public static function sendUserEmailVerificationMail($emails = [], $subject = null, $message = null, $user = null, $ownerUserId = null)
    {
        if (env('MAIL_STATUS', 0) == 1 && env('MAIL_USERNAME')) {
            if (count($emails)) {
                foreach ($emails as $key => $email) {
                    try {
                        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                            $details['subject'] = $subject;
                            $details['message'] = $message;
                            $details['user'] = $user;
                            // send mail
                            Mail::to($email)->send(new UserEmailVerification($details));
                            // log generate
                            Log::channel('sms-mail')->info('email : ' . $email . ', subject : ' . $subject . ', message : ' . $message . 'key : ' . $key . ', date : ' . date('d-m-Y'));
                            self::historyStore($ownerUserId, $email, $subject, $message, SMS_STATUS_DELIVERED);
                        } else {
                            throw new Exception('Email ' . $email . ' is not valid');
                        }
                    } catch (Exception $e) {
                        Log::channel('sms-mail')->info($e->getMessage());
                        self::historyStore($ownerUserId, $email, $subject, $message, SMS_STATUS_FAILED, $e->getMessage());
                    }
                }
                return 'success';
            } else {
                return __('No email found');
            }
        } else {
            return __('Smtp setting not enabled');
        }
    }

    public static function sendReminderMail($emails = [], $subject = null, $message = null, $ownerUserId = null)
    {
        if (env('MAIL_STATUS', 0) == 1 && env('MAIL_USERNAME')) {
            if (count($emails)) {
                foreach ($emails as $key => $email) {
                    try {
                        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                            $details['subject'] = $subject;
                            $details['message'] = $message;
                            // send mail
                            Mail::to($email)->send(new ReminderMail($details));
                            // log generate
                            Log::channel('sms-mail')->info('email : ' . $email . ', subject : ' . $subject . ', message : ' . $message . 'key : ' . $key . ', date : ' . date('d-m-Y'));
                            self::historyStore($ownerUserId, $email, $subject, $message, SMS_STATUS_DELIVERED);
                        } else {
                            throw new Exception('Email ' . $email . ' is not valid');
                        }
                    } catch (Exception $e) {
                        Log::channel('sms-mail')->info($e->getMessage());
                        self::historyStore($ownerUserId, $email, $subject, $message, SMS_STATUS_FAILED, $e->getMessage());
                    }
                }
                return 'success';
            } else {
                return __('No email found');
            }
        } else {
            return __('Smtp setting not enabled');
        }
    }

    public static function sendSubscriptionSuccessMail($emails = [], $subject = null, $message = null, $ownerUserId, $title = null, $method = null, $status = null, $amount = 0, $duration = 30)
    {
        if (env('MAIL_STATUS', 0) == 1 && env('MAIL_USERNAME')) {
            if (count($emails)) {
                foreach ($emails as $key => $email) {
                    try {
                        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                            $details['subject'] = $subject;
                            $details['message'] = $message;
                            $details['title'] = $title;
                            $details['method'] = $method;
                            $details['status'] = $status;
                            $details['amount'] = $amount;
                            $details['duration'] = $duration;
                            // send mail
                            Mail::to($email)->send(new SubscriptionSuccessMail($details));
                            // log generate
                            Log::channel('sms-mail')->info('email : ' . $email . ', subject : ' . $subject . ', message : ' . $message . 'key : ' . $key . ', date : ' . date('d-m-Y'));
                            self::historyStore($ownerUserId, $email, $subject, $message, SMS_STATUS_DELIVERED);
                        } else {
                            throw new Exception('Email ' . $email . ' is not valid');
                        }
                    } catch (Exception $e) {
                        Log::channel('sms-mail')->info($e->getMessage());
                        self::historyStore($ownerUserId, $email, $subject, $message, SMS_STATUS_FAILED, $e->getMessage());
                    }
                }
                return 'success';
            } else {
                return __('No email found');
            }
        } else {
            return __('Smtp setting not enabled');
        }
    }

    public static function sendInvoiceMail($emails = [], $subject = null, $message = null, $ownerUserId, $title = null, $amount = 0, $dueDate = null, $month = null, $invoiceNo = null, $status)
    {
        if (env('MAIL_STATUS', 0) == 1 && env('MAIL_USERNAME')) {
            if (count($emails)) {
                foreach ($emails as $key => $email) {
                    try {
                        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                            $details['subject'] = $subject;
                            $details['message'] = $message;
                            $details['title'] = $title;
                            $details['amount'] = $amount;
                            $details['dueDate'] = $dueDate;
                            $details['month'] = $month;
                            $details['invoiceNo'] = $invoiceNo;
                            $details['status'] = $status;
                            // send mail
                            Mail::to($email)->send(new InvoiceMail($details));
                            // log generate
                            Log::channel('sms-mail')->info('email : ' . $email . ', subject : ' . $subject . ', message : ' . $message . 'key : ' . $key . ', date : ' . date('d-m-Y'));
                            self::historyStore($ownerUserId, $email, $subject, $message, SMS_STATUS_DELIVERED);
                        } else {
                            throw new Exception('Email ' . $email . ' is not valid');
                        }
                    } catch (Exception $e) {
                        Log::channel('sms-mail')->info($e->getMessage());
                        self::historyStore($ownerUserId, $email, $subject, $message, SMS_STATUS_FAILED, $e->getMessage());
                    }
                }
                return 'success';
            } else {
                return __('No email found');
            }
        } else {
            return __('Smtp setting not enabled');
        }
    }

    public static function sendContactThankYouMail($emails = [], $subject = null, $message = null, $title = null)
    {
        if (env('MAIL_STATUS', 0) == 1 && env('MAIL_USERNAME')) {
            if (count($emails)) {
                foreach ($emails as $key => $email) {
                    try {
                        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                            $details['subject'] = $subject;
                            $details['title'] = $title;
                            $details['message'] = $message;
                            // send mail
                            Mail::to($email)->send(new ThankYouMail($details));
                            // log generate
                            Log::channel('sms-mail')->info('email : ' . $email . ', subject : ' . $subject . ', message : ' . $message . 'key : ' . $key . ', date : ' . date('d-m-Y'));
                        } else {
                            throw new Exception('Email ' . $email . ' is not valid');
                        }
                    } catch (Exception $e) {
                        Log::channel('sms-mail')->info($e->getMessage());
                    }
                }
                return 'success';
            } else {
                return __('No email found');
            }
        } else {
            return __('Smtp setting not enabled');
        }
    }

    public static function sendCustomizeMail($emails, $subject, $content)
    {
        if (env('MAIL_STATUS', 0) == 1 && env('MAIL_USERNAME')) {
            if (count($emails)) {
                foreach ($emails as $key => $email) {
                    try {
                        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                            $details['subject'] = $subject;
                            $details['content'] = $content;
                            // send mail
                            Mail::to($email)->send(new CustomizeMail($details));
                            // log generate
                            Log::channel('sms-mail')->info('email : ' . $email . ', subject : ' . $subject .  ', date : ' . date('d-m-Y'));
                        } else {
                            throw new Exception('Email ' . $email . ' is not valid');
                        }
                    } catch (Exception $e) {
                        Log::channel('sms-mail')->info($e->getMessage());
                    }
                }
                return 'success';
            } else {
                return __('No email found');
            }
        } else {
            return __('Smtp setting not enabled');
        }
    }

    public static function historyStore($ownerUserId, $email, $subject, $message, $status, $error = null)
    {
        $history = new MailHistory();
        $history->owner_user_id = $ownerUserId;
        $history->host = env('MAIL_HOST');
        $history->email = $email;
        $history->subject = $subject;
        $history->message = $message;
        $history->status = $status;
        $history->date = now();
        $history->error = $error;
        $history->save();
    }
}
