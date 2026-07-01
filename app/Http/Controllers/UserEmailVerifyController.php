<?php

namespace App\Http\Controllers;

use App\Models\EmailTemplate;
use App\Services\SmsMail\MailService;
use App\Services\UserEmailVerifyService;
use Illuminate\Http\Request;

class UserEmailVerifyController extends Controller
{
    public $userEmailVerifyService;

    public function __construct()
    {
        $this->userEmailVerifyService = new UserEmailVerifyService;
    }

    public function emailVerified($token)
    {
        $verified =  $this->userEmailVerifyService->emailVerified($token);
        if ($verified == true) {
            return redirect()->route('login')->with('success', __('Congratulations! Successfully verified your email.'));
        } else {
            return redirect(route('login'));
        }
    }

    public function emailVerify($token)
    {
        $user = $this->userEmailVerifyService->getUserByToken($token);

        if (!is_null($user)) {
            if ($user->status == USER_STATUS_ACTIVE) {
                return redirect()->route('login');
            }
        } else {
            return redirect()->route('login')->with('error', __(SOMETHING_WENT_WRONG));
        }
        return view('auth.verify', compact('token'));
    }

    public function emailVerifyResend($token)
    {
        $user = $this->userEmailVerifyService->getUserByToken($token);

        if (getOption('email_verification_status', 0) == 1) {
            if ($user) {
                $subject = __('Resent Account Verification') . ' ' . getOption('app_name');
                $message = __('Please verify your account');
                $emails = [$user->email];
                $ownerUserId = $user->role == USER_ROLE_OWNER ? $user->id : $user->owner_user_id;

                $mailService = new MailService;
                $template = EmailTemplate::where('owner_user_id', $ownerUserId)->where('category', EMAIL_TEMPLATE_EMAIL_VERIFY)->where('status', ACTIVE)->first();
                if ($template) {
                    $customizedFieldsArray = [
                        '{{user_name}}' => $user->name,
                        '{{verify_link}}' => route('user.email.verified', $user->verify_token),
                        '{{otp}}' => $user->otp,
                        '{{app_name}}' => getOption('app_name'),
                    ];
                    $content = getEmailTemplate($template->body, $customizedFieldsArray);
                    $mailService->sendCustomizeMail($emails, $template->subject, $content);
                } else {
                    $mailService->sendUserEmailVerificationMail($emails, $subject, $message, $user, $ownerUserId);
                }

                return redirect()->route('login')->with('success', __(SENT_SUCCESSFULLY));
            }
        } else {
            return redirect()->route('login')->with('error', __(SOMETHING_WENT_WRONG));
        }
    }
}
