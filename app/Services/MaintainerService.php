<?php

namespace App\Services;

use App\Models\EmailTemplate;
use App\Models\FileManager;
use App\Models\Maintainer;
use App\Models\Property;
use App\Models\User;
use App\Services\SmsMail\MailService;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class MaintainerService
{
    use ResponseTrait;

    public function getAllData()
    {
        $maintainer = Maintainer::query()
            ->with('properties')
            ->join('users', 'maintainers.user_id', '=', 'users.id')
            ->join('users as owner', 'maintainers.owner_user_id', '=', 'owner.id')
            ->leftJoin('file_managers', ['file_managers.origin_id' => 'users.id', 'file_managers.origin_type' => (DB::raw("'App\\\Models\\\User'"))])
            ->whereNull('users.deleted_at')
            ->where('maintainers.owner_user_id', getOwnerUserId())
            ->select(DB::raw('CONCAT(owner.first_name, " " ,owner.last_name) as owner'), 'maintainers.id', 'maintainers.user_id', 'users.first_name', 'users.last_name', 'users.email', 'users.status', 'users.contact_number', 'file_managers.file_name', 'file_managers.folder_name');
        return datatables($maintainer)
            ->addIndexColumn()
            ->addColumn('image', function ($maintainer) {
                return '<div class="tenants-tbl-info-object tbl-info-property-img d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <img src="' . getFileUrl($maintainer->folder_name, $maintainer->file_name) . '"
                                class="rounded avatar-md tbl-user-image"
                                alt="' . $maintainer->file_name . '">
                            </div>
                        </div>';
            })
            ->editColumn('name', function ($maintainer) {
                return $maintainer->first_name . ' ' . $maintainer->last_name;
            })
            ->addColumn('email', function ($maintainer) {
                return $maintainer->email;
            })
            ->addColumn('contact_number', function ($maintainer) {
                return $maintainer->contact_number;
            })
            ->addColumn('property', function ($maintainer) {
                $data = '';
                foreach ($maintainer->properties as $property) {
                    $data .= '<span class="badge bg-info me-1">' . $property->name . '</span>';
                }
                return $data;
            })
            ->addColumn('status', function ($maintainer) {
                if ($maintainer->status == USER_STATUS_ACTIVE) {
                    return '<div class="status-btn status-btn-green font-13 radius-4">Active</div>';
                } else if ($maintainer->status == USER_STATUS_DELETED) {
                    return '<div class="status-btn status-btn-orange font-13 radius-4">Deleted</div>';
                } else {
                    return '<div class="status-btn status-btn-orange font-13 radius-4">Deactivate</div>';
                }
            })
            ->addColumn('action', function ($maintainer) {
                $id = $maintainer->id;
                return '<div class="tbl-action-btns d-inline-flex">
                            <button type="button" class="p-1 tbl-action-btn edit" data-id="' . $id . '" title="' . __('Edit') . '"><span class="iconify" data-icon="clarity:note-edit-solid"></span></button>
                            <button onclick="deleteItem(\'' . route('owner.maintainer.delete', $id) . '\', \'allDatatable\')" class="p-1 tbl-action-btn"   title="' . __('Delete') . '"><span class="iconify" data-icon="ep:delete-filled"></span></button>
                        </div>';
            })
            ->rawColumns(['image', 'property', 'status', 'action'])
            ->make(true);
    }

    public function allData()
    {
        $maintainers = Maintainer::query()
            ->join('users', 'maintainers.user_id', '=', 'users.id')
            ->leftJoin('file_managers', ['file_managers.origin_id' => 'users.id', 'file_managers.origin_type' => (DB::raw("'App\\\Models\\\User'"))])
            ->where('maintainers.owner_user_id', getOwnerUserId())
            ->select('maintainers.id', 'users.first_name', 'users.last_name', 'users.email', 'users.status', 'users.contact_number', 'file_managers.folder_name', 'file_managers.file_name')
            ->get();

        foreach ($maintainers as $maintainer) {
            $maintainer->image = getFileUrl($maintainer->folder_name, $maintainer->file_name);
        }

        return $maintainers;
    }

    public function getAll()
    {
        return  Maintainer::query()
            ->join('users', 'maintainers.user_id', '=', 'users.id')
            ->where('maintainers.owner_user_id', getOwnerUserId())
            ->select('maintainers.user_id', 'users.*')
            ->get();
    }

    public function store($request)
    {
        DB::beginTransaction();
        try {
            $id = $request->get('id', '');
            if ($id != '') {
                $maintainer = Maintainer::where('owner_user_id', getOwnerUserId())->findOrFail($request->id);
                $user = User::where('owner_user_id', getOwnerUserId())->findOrFail($maintainer->user_id);
            } else {
                if (!getOwnerLimit(RULES_MAINTAINER) > 0) {
                    throw new Exception(__('Your Maintainer Limit finished'));
                }
                $maintainer = new Maintainer();
                $user = new User();
            }

            // User
            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name;
            $user->email = $request->email;
            $user->contact_number = $request->contact_number;
            if ($request->password) {
                $user->password = Hash::make($request->password);
            }
            $user->role = USER_ROLE_MAINTAINER;
            $user->owner_user_id = getOwnerUserId();
            $user->status = $request->status;
            $user->save();

            // Maintainer
            $maintainer->user_id = $user->id;
            if (isset($request->property_id) && count($request->property_id) > 0) {
                Property::where('maintainer_id', $maintainer->user_id)
                    ->update(['maintainer_id' =>  null]);
                foreach ($request->property_id as $propertyId) {
                    Property::where('id', $propertyId)
                        ->update(['maintainer_id' =>  $maintainer->user_id]);
                }
            }
            $maintainer->owner_user_id = getOwnerUserId();
            $maintainer->save();

            /*File Manager Call upload*/
            if ($request->hasFile('image')) {
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
            /*End*/
            DB::commit();
            if (getOption('send_email_status', 0) == ACTIVE) {
                if ($id == '') {
                    $emails = [$user->email];
                    $subject = getOption('app_name') . ' ' . __('welcome you');
                    $message = __('You have successfully been registered');
                    $ownerUserId = getOwnerUserId();
                    $password = $request->password;

                    $mailService = new MailService;
                    $template = EmailTemplate::where('owner_user_id', $ownerUserId)->where('category', EMAIL_TEMPLATE_SIGN_UP)->where('status', ACTIVE)->first();
                    if ($template) {
                        $customizedFieldsArray = [
                            '{{email}}' => $user->email,
                            '{{password}}' => $password,
                            '{{app_name}}' => getOption('app_name')
                        ];
                        $content = getEmailTemplate($template->body, $customizedFieldsArray);
                        $mailService->sendCustomizeMail($emails, $template->subject, $content);
                    } else {
                        $mailService->sendSignUpMail($emails, $subject, $message, $ownerUserId, $password);
                    }
                }
            }
            $message = $request->id ? __(UPDATED_SUCCESSFULLY) : __(CREATED_SUCCESSFULLY);
            return $this->success([], $message);
        } catch (Exception $e) {
            DB::rollBack();
            $message = getErrorMessage($e, $e->getMessage());
            return $this->error([],  $message);
        }
    }

    public function getById($id)
    {
        return Maintainer::findOrFail($id);
    }

    public function getInfo($id)
    {
        $maintainer = Maintainer::query()
            ->with('properties:maintainer_id,id')
            ->join('users', 'maintainers.user_id', '=', 'users.id')
            ->select('maintainers.id', 'maintainers.user_id', 'users.first_name', 'users.last_name', 'users.email', 'users.contact_number','users.status')
            ->where('maintainers.owner_user_id', getOwnerUserId())
            ->findOrFail($id);

        $maintainer->property_id = array_map('strval', $maintainer->properties->pluck('id')->toArray());
        $maintainer->image = $maintainer->user->image;
        return $maintainer;
    }

    public function details($id)
    {
        $maintainer = Maintainer::query()
            ->with('properties:maintainer_id,id')
            ->join('users', 'maintainers.user_id', '=', 'users.id')
            ->leftJoin('file_managers', ['file_managers.origin_id' => 'users.id', 'file_managers.origin_type' => (DB::raw("'App\\\Models\\\User'"))])
            ->select('maintainers.id', 'maintainers.user_id', 'users.first_name', 'users.last_name', 'users.email', 'users.contact_number', DB::raw('CONCAT(file_managers.folder_name,"/",file_managers.file_name) as image'))
            ->where('maintainers.owner_user_id', getOwnerUserId())
            ->findOrFail($id);
        $maintainer->property_id = array_map('strval', $maintainer->properties->pluck('id')->toArray());
        return $maintainer;
    }

    public function deleteById($id)
    {
        DB::beginTransaction();
        try {
            $maintainer =  Maintainer::where('owner_user_id', getOwnerUserId())->findOrFail($id);
            $user = User::find($maintainer->user_id);
            $user->delete();
            $maintainer->delete();
            DB::commit();
            $message = __(DELETED_SUCCESSFULLY);
            return $this->success([], $message);
        } catch (\Exception $e) {
            DB::rollBack();
            $message = getErrorMessage($e, $e->getMessage());
            return $this->error([],  $message);
        }
    }
}
