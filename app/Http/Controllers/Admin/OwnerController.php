<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\OwnerRegisterRequest;
use App\Models\Owner;
use App\Models\Package;
use App\Models\User;
use App\Services\OwnerService;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class OwnerController extends Controller
{
    use ResponseTrait;
    public $ownerService;
    public function __construct()
    {
        $this->ownerService = new OwnerService;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            return $this->ownerService->getAllData($request);
        } else {
            $data['pageTitle'] = __('Owners');
            return view('admin.owner.index', $data);
        }
    }

    public function getInfo(Request $request)
    {
        $owner = User::where('role', USER_ROLE_OWNER)->findOrFail($request->id);
        return $owner;
    }

    public function update(Request $request)
    {
        $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255', 'unique:users,email,' . $request->id],
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['nullable', 'string', 'min:6', 'confirmed'],
        ]);

        try {
            $owner = User::where('role', USER_ROLE_OWNER)->findOrFail($request->id);
            $owner->first_name = $request->first_name;
            $owner->last_name = $request->last_name;
            $owner->contact_number = $request->contact_number;
            $owner->email = $request->email;
            if (!is_null($request->password)) {
                $owner->password =  Hash::make($request->password);
            }
            $owner->status = $request->status;
            $owner->save();
            return $this->success([], __(UPDATED_SUCCESSFULLY));
        } catch (Exception $e) {
            $message = getErrorMessage($e, $e->getMessage());
            return $this->error([], $message);
        }
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
            $user->status = $request->status;
            $user->role = USER_ROLE_OWNER;
            $user->verify_token = str_replace('-', '', Str::uuid()->toString());
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
            $message = __(CREATED_SUCCESSFULLY);
            return $this->success([], $message);
        } catch (Exception $e) {
            DB::rollBack();
            $message = getErrorMessage($e, $e->getMessage());
            return $this->error([],  $message);
        }
    }

    public function delete($id)
    {
        return $this->ownerService->delete($id);
    }
}
