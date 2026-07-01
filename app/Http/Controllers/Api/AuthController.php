<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\OtpReSendRequest;
use App\Http\Requests\OtpVerifyRequest;
use App\Http\Requests\OwnerRegisterRequest;
use App\Models\EmailTemplate;
use App\Models\Owner;
use App\Models\Package;
use App\Models\User;
use App\Services\SmsMail\MailService;
use App\Traits\ResponseTrait;
use Carbon\Carbon;
use Exception;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    use SendsPasswordResetEmails;
    use ResponseTrait;

    public function ownerRegister(OwnerRegisterRequest $request)
    {
        DB::beginTransaction();
        try {
            $user = new User();
            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name;
            $user->contact_number = $request->contact_number;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->status = USER_STATUS_UNVERIFIED;
            $user->role = USER_ROLE_OWNER;
            $user->verify_token = str_replace('-', '', Str::uuid()->toString());
            $user->otp = rand(100000, 999999);
            $user->otp_expire = now()->addMinute(5);
            $user->save();

            $owner = new Owner();
            $owner->user_id = $user->id;
            $owner->save();

            $duration = (int) getOption('trail_duration', 1);

            $defaultPackage = Package::where(['is_trail' => ACTIVE])->first();
            if ($defaultPackage) {
                setUserPackage($user->id, $defaultPackage, $duration, 1);
            }

            syncMissingGateway();
//            setOwnerGateway($user->id);
            DB::commit();
            // login credential
            $response['email_verification_status'] = false;
            if (getOption('send_email_status', 0) == ACTIVE) {
                $emails = [$user->email];
                $subject = getOption('app_name') . ' ' . __('welcome you');
                $message = __('You have successfully been registered');
                $ownerUserId = $user->id;

                $mailService = new MailService;
                $mailService->sendWelcomeMail($emails, $subject, $message, $ownerUserId);

                if (getOption('email_verification_status', 0) == ACTIVE) {
                    $subject = __('Account Verification') . ' ' . getOption('app_name');
                    $message = __('Thank you for create new account. Please verify your account');
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
                    $response['email_verification_status'] = true;
                    $response['email'] = $user->email;
                } else {
                    $user->status = USER_STATUS_ACTIVE;
                    $user->email_verified_at = Carbon::now()->format("Y-m-d H:i:s");
                    $user->save();
                }
            } else {
                $user->status = USER_STATUS_ACTIVE;
                $user->email_verified_at = Carbon::now()->format("Y-m-d H:i:s");
                $user->save();
            }

            $message = __(CREATED_SUCCESSFULLY);

            return $this->success($response, $message);
        } catch (Exception $e) {
            DB::rollBack();
            return $this->error([], $e->getMessage());
        }
    }

    public function otpVerify(OtpVerifyRequest $request)
    {
        try {
            $user = User::where('email', $request->email)->where('otp', $request->otp)->first();
            if ($user) {
                if ($user->otp_expire >= now()) {
                    $user->status = USER_STATUS_ACTIVE;
                    $user->email_verified_at = Carbon::now()->format("Y-m-d H:i:s");
                    $user->save();
                    return $this->success([], __(EMAIL_VERIFIED_SUCCESSFULLY));
                } else {
                    throw new Exception(__('The Otp has expired. Please re-send'));
                }
            } else {
                throw new Exception(__('Invalid otp'));
            }
        } catch (Exception $e) {
            return $this->error([], $e->getMessage());
        }
    }

    public function otpReSend(OtpReSendRequest $request)
    {
        try {
            $user = User::where('email', $request->email)->firstOrFail();
            $response['email_verification_status'] = false;
            if (getOption('send_email_status', 0) == ACTIVE) {
                if (getOption('email_verification_status', 0) == ACTIVE) {
                    $subject = __('Resend Account Verification') . ' ' . getOption('app_name');
                    $message = __('Please verify your account');
                    $user->otp = rand(100000, 999999);
                    $user->otp_expire = now()->addMinute(5);
                    $user->save();
                    $ownerUserId = $user->id;
                    $emails = [$user->email];
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

                    $response['email_verification_status'] = true;
                    $response['email'] = $user->email;
                    return $this->success($response, __(SENT_SUCCESSFULLY));
                }
            }
            $user->status = USER_STATUS_ACTIVE;
            $user->email_verified_at = Carbon::now()->format("Y-m-d H:i:s");
            $user->save();
            return $this->success($response, __(EMAIL_VERIFIED_SUCCESSFULLY));
        } catch (Exception $e) {
            return $this->error([], $e->getMessage());
        }
    }

    public function login(LoginRequest $request)
    {
        try {
            $field = 'email';
            if (filter_var($request->input('email'), FILTER_VALIDATE_EMAIL)) {
                $field = 'email';
            } elseif (is_numeric($request->input('email'))) {
                $field = 'contact_number';
            }

            if (auth()->attempt([$field => $request->email, 'password' => $request->password])) {
                $user = auth()->user();
                if (isset($user) && ($user->status == USER_STATUS_UNVERIFIED && $user->role != USER_ROLE_ADMIN)) {
                    if (getOption('email_verification_status', 0) == 1) {
                        if (is_null($user->verify_token)) {
                            $user->verify_token = str_replace('-', '', Str::uuid()->toString());
                            $user->save();
                        }
                        throw new Exception(__(VERIFY_YOUR_EMAIL));
                    } else {
                        $user->status = USER_STATUS_ACTIVE;
                        $user->email_verified_at = Carbon::now()->format("Y-m-d H:i:s");
                        $user->save();
                        throw new Exception(__('Please Try Again'));
                    }
                } elseif (isset($user) && ($user->status == USER_STATUS_INACTIVE)) {
                    throw new Exception(__('Your account is inactive. Please contact with admin'));
                } elseif (isset($user) && ($user->status == USER_STATUS_DELETED)) {
                    throw new Exception(__('Your account has been deleted.'));
                } elseif (isset($user) && ($user->status == USER_STATUS_ACTIVE)) {
                    if (isset($user) && ($user->role == USER_ROLE_TENANT)) {
                        if (!is_null($user->tenant->property_id) && !is_null($user->tenant->property_id)) {
                            $response['access_token'] = $user->createToken(Str::random(40))->accessToken;
                            $response['user'] = $user->only(['id', 'first_name', 'last_name', 'email', 'contact_number', 'role', 'owner_user_id']);
                            $response['user']['image'] = $user->image;
                            $message = __(LOGIN_SUCCESSFUL);
                        } else {
                            throw new Exception(__('Your account is inactive. Please contact with admin'));
                        }
                    } else {
                        $response['access_token'] = $user->createToken(Str::random(40))->accessToken;
                        $response['user'] = $user->only(['id', 'first_name', 'last_name', 'email', 'contact_number', 'role', 'owner_user_id']);
                        $response['user']['image'] = $user->image;
                        $message = __(LOGIN_SUCCESSFUL);
                    }
                }
                return $this->success($response, $message);
            } else {
                throw new Exception(__('Email or password is incorrect'));
            }
        } catch (Exception $e) {
            return $this->error([], $e->getMessage());
        }
    }

    public function forgotPassword(Request $request)
    {
        try {
            if (env('MAIL_STATUS') == ACTIVE && env('MAIL_USERNAME')) {
                $this->validateEmail($request);
                $response = $this->broker()->sendResetLink(
                    $this->credentials($request)
                );
                $response == Password::RESET_LINK_SENT
                    ? $this->sendResetLinkResponse($request, $response)
                    : $this->sendResetLinkFailedResponse($request, $response);
                return $this->success([], __($response));
            } else {
                throw new Exception(__('Mail credentials is off now. Please try again later'));
            }
        } catch (Exception $e) {
            return $this->error([], $e->getMessage());
        }
    }
}
