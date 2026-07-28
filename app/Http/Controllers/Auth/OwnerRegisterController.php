<?php
namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use App\Http\Requests\OwnerRegisterRequest;
use App\Models\Owner;
use App\Models\Package;
use App\Models\User;
use App\Services\SmsMail\MailService;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class OwnerRegisterController extends Controller
{
    use ResponseTrait;

    public function showForm()
    {
        return view('auth.owner_register_form');
    }

    public function store(OwnerRegisterRequest $request)
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
            $user->save();

            $owner = new Owner();
            $owner->user_id = $user->id;
            $owner->save();
            $duration = (int) getOption('trail_duration', 1);
            $defaultPackage = Package::where(['is_trail' => ACTIVE])->first();
            if ($defaultPackage) {
                setUserPackage($user->id, $defaultPackage, $duration);
            }
            syncMissingGateway();
            DB::commit();

            // Routed through MailService (rather than sending directly) so this
            // shares one place with every other verification/resend email in
            // the app — the single point a future SMS/WhatsApp notification
            // dispatcher needs to extend, instead of every call site that
            // sends a message.
            MailService::sendUserEmailVerificationMail(
                [$user->email],
                'Verify Your Email Address',
                'Please click the button below to verify your email address.',
                $user,
                $user->id
            );

            return redirect()->route('login')->with('success', __('Registration successful! Check email for verification.'));
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', __(SOMETHING_WENT_WRONG))->withInput();
        }
    }
}
