<?php
namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use App\Http\Requests\OwnerRegisterRequest;
use App\Models\Owner;
use App\Models\Package;
use App\Models\User;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserEmailVerification;

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
            
            $content = [
                'subject' => 'Verify Your Email Address',
                'user' => $user,
                'message' => 'Please click the button below to verify your email address.',
            ];
            
            Mail::to($user->email)->send(new UserEmailVerification($content));
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
            return redirect()->route('login')->with('success', 'Registration successful! Check Email for verification.');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }
}
