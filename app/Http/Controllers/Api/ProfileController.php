<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\ProfileDeleteRequest;
use App\Http\Requests\ProfileRequest;
use App\Models\FileManager;
use App\Models\Owner;
use App\Models\Tenant;
use App\Models\TenantDetails;
use App\Models\User;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class ProfileController extends Controller
{
    use ResponseTrait;
    public function profileDetails()
    {
        if (auth()->user()->role == USER_ROLE_TENANT) {
            $data['tenant'] = Tenant::where('user_id', auth()->id())->select(['id', 'job', 'family_member', 'age'])->first();
            $data['details'] = TenantDetails::where('tenant_id', $data['tenant']->id)->first();
            $data['details']?->makeHidden(['created_at', 'updated_at', 'deleted_at']);
        } elseif (auth()->user()->role == USER_ROLE_OWNER) {
            $data['owner'] = Owner::query()
                ->leftJoin('file_managers', 'owners.logo_id', '=', 'file_managers.id')
                ->where('user_id', auth()->id())
                ->select(['owners.print_name', 'owners.print_address', 'owners.print_contact', 'file_managers.folder_name', 'file_managers.file_name'])
                ->first();
            $data['owner']->print_logo = getFileUrl($data['owner']->folder_name, $data['owner']->file_name);
        }
        $data['image'] = auth()->user()->image;
        $data['user'] = auth()->user()?->makeHidden(['id', 'email_verified_at', 'created_by', 'verify_token', 'otp', 'otp_expire', 'deleted_at', 'created_at', 'updated_at']);
        return $this->success($data);
    }

    public function profileUpdate(ProfileRequest $request)
    {
        DB::beginTransaction();
        try {
            $user = User::find(auth()->id());
            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name;
            $user->contact_number = $request->contact_number;
            $user->date_of_birth = $request->date_of_birth;
            $user->nid_number = $request->nid_number;
            if (auth()->user()->role == USER_ROLE_ADMIN || auth()->user()->role == USER_ROLE_OWNER) {
                $user->email = $request->email;
            }
            $user->save();

            if (auth()->user()->role == USER_ROLE_TENANT) {
                $tenant = Tenant::where('user_id', auth()->id())->first();
                $tenant->job = $request->job;
                $tenant->family_member = $request->family_member;
                $tenant->age = $request->age;
                $tenant->save();

                $details = TenantDetails::where('tenant_id', $tenant->id)->first();
                $details->permanent_country_id = $request->permanent_country_id;
                $details->permanent_state_id = $request->permanent_state_id;
                $details->permanent_city_id = $request->permanent_city_id;
                $details->permanent_address = $request->permanent_address;
                $details->permanent_zip_code = $request->permanent_zip_code;

                $details->previous_country_id = $request->previous_country_id;
                $details->previous_state_id = $request->previous_state_id;
                $details->previous_city_id = $request->previous_city_id;
                $details->previous_address = $request->previous_address;
                $details->previous_zip_code = $request->previous_zip_code;
                $details->save();
            }
            /*File Manager Call upload*/
            if ($request->image) {
                $new_file = FileManager::where('origin_type', 'App\Models\User')->where('origin_id', $user->id)->first();

                if ($new_file) {
                    $new_file->removeFile();
                    $upload = $new_file->updateUpload($new_file->id, 'User', $request->image);
                } else {
                    $new_file = new FileManager();
                    $upload = $new_file->upload('User', $request->image);
                }

                if ($upload['status']) {
                    $upload['file']->origin_id = $user->id;
                    $upload['file']->origin_type = "App\Models\User";
                    $upload['file']->save();
                } else {
                    throw new Exception($upload['message']);
                }
            }
            DB::commit();
            return $this->success([], __(UPDATED_SUCCESSFULLY));
        } catch (\Exception $e) {
            DB::rollback();
            return $this->error([], $e->getMessage());
        }
    }

    public function changePasswordUpdate(ChangePasswordRequest $request)
    {
        try {
            $hashedPassword = auth()->user()->password;
            if (Hash::check($request->current_password, $hashedPassword)) {
                $user = User::find(auth()->id());
                $user->password = Hash::make($request->password);
                $user->save();
                return $this->success([], __(UPDATED_SUCCESSFULLY));
            } else {
                throw new Exception(__('Current password does not matched!'));
            }
        } catch (Exception $e) {
            return $this->error([], $e->getMessage());
        }
    }

    public function deleteAccount(ProfileDeleteRequest $request)
    {
        DB::beginTransaction();
        try {
            $authUser = auth()->user();
            if (!Hash::check($request->password, $authUser->password) || $authUser->email != $request->email) {
                throw new Exception(__('Information does not matched!'));
            }
            if ($authUser->role == USER_ROLE_ADMIN || $authUser->role == USER_ROLE_OWNER) {
                throw new Exception(__('This is admin account'));
            }
            $user = User::find($authUser->id);
            $user->status = USER_STATUS_DELETED;
            $user->save();
            DB::commit();
            auth()->user()->token()->revoke();
            return $this->success([], __('Account Deleted Successfully'));
        } catch (Exception $e) {
            DB::rollBack();
            $message = getErrorMessage($e, $e->getMessage());
            return $this->error([],  $message);
        }
    }
}
