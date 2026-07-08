<?php

namespace App\Services;

use App\Models\EmailTemplate;
use App\Models\FileManager;
use App\Models\Invoice;
use App\Models\Tenant;
use App\Models\TenantDetails;
use App\Models\User;
use App\Services\SmsMail\MailService;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class TenantService
{
    use ResponseTrait;

    public function getAll()
    {
        $data = Tenant::query()
            ->join('users', 'tenants.user_id', '=', 'users.id')
            ->leftJoin('properties', 'tenants.property_id', '=', 'properties.id')
            ->leftJoin('property_units', 'tenants.unit_id', '=', 'property_units.id')
            ->leftJoin(DB::raw('(select tenant_id, SUM(amount) as due from invoices where status = 0 AND deleted_at IS NULL group By tenant_id) as inv'), ['inv.tenant_id' => 'tenants.id'])
            ->leftJoin(DB::raw('(select tenant_id, MAX(updated_at) as last_payment from invoices where status = 1 AND deleted_at IS NULL group By tenant_id) as inv_last'), ['inv_last.tenant_id' => 'tenants.id'])
            ->whereNull('users.deleted_at')
            ->select(['tenants.*', 'inv.due', 'inv_last.last_payment', 'users.first_name', 'users.last_name', 'users.status as userStatus', 'users.contact_number', 'users.email', 'property_units.unit_name', 'properties.name as property_name'])
            ->where('tenants.owner_user_id', getOwnerUserId())
            ->get();
        return $data?->makeHidden(['created_at', 'updated_at', 'deleted_at']);
    }

    public function getActiveAll()
    {
        return Tenant::query()
            ->join('users', 'tenants.user_id', '=', 'users.id')
            ->whereNull('users.deleted_at')
            ->select(['tenants.*', 'users.first_name', 'users.last_name', 'users.contact_number', 'users.email'])
            ->where('tenants.status', TENANT_STATUS_ACTIVE)
            ->where('tenants.owner_user_id', getOwnerUserId())
            ->get();
    }
    
    public function getActiveTenantByUnitId($unitId)
    {
        return Tenant::where('unit_id', $unitId)
            ->where('status', TENANT_STATUS_ACTIVE)
            ->first();
    }

    public function getAllData()
    {
        $tenants = Tenant::query()
            ->join('users', 'tenants.user_id', '=', 'users.id')
            ->whereNull('users.deleted_at')
            ->leftJoin('properties', 'tenants.property_id', '=', 'properties.id')
            ->leftJoin('property_units', 'tenants.unit_id', '=', 'property_units.id')
            ->leftJoin(DB::raw('(select tenant_id, SUM(amount) as due from invoices where status = 0 AND deleted_at IS NULL group By tenant_id) as inv'), ['inv.tenant_id' => 'tenants.id'])
            ->leftJoin(DB::raw('(select tenant_id, MAX(updated_at) as last_payment from invoices where status = 1 AND deleted_at IS NULL group By tenant_id) as inv_last'), ['inv_last.tenant_id' => 'tenants.id'])
            ->select(['tenants.*', 'inv.due', 'inv_last.last_payment', 'users.first_name', 'users.last_name', 'users.status as userStatus', 'users.contact_number', 'users.email', 'property_units.unit_name', 'properties.name as property_name'])
            ->where('tenants.owner_user_id', getOwnerUserId());

        return datatables($tenants)
            ->addIndexColumn()
            ->addColumn('name', function ($tenant) {
                return '<div class="tenants-tbl-info-object d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <img src="' . $tenant->image . '"
                            class="rounded-circle avatar-md tbl-user-image"
                            alt="">
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6>' . $tenant->first_name . ' ' . $tenant->last_name . '</h6>
                            <p class="font-13">' . $tenant->email . '</p>
                        </div>
                    </div>';
            })
            ->addColumn('property', function ($tenant) {
                return $tenant->property_name;
            })
            ->addColumn('contact', function ($tenant) {
                return $tenant->contact_number;
            })
            ->addColumn('last_payment', function ($tenant) {
                return $tenant->last_payment ? date('Y-m-d', strtotime($tenant->last_payment)) : 'N/A';
            })
            ->addColumn('due', function ($tenant) {
                return currencyPrice($tenant->due);
            })
            ->addColumn('general_rent', function ($tenant) {
                return currencyPrice($tenant->general_rent);
            })
            ->addColumn('unit', function ($tenant) {
                return $tenant->unit_name;
            })
            ->addColumn('status', function ($tenant) {
                $html = '';
                if ($tenant->userStatus == USER_STATUS_DELETED) {
                    $html = ' <div class="status-btn status-btn-orange font-13 radius-4">' . __('Deleted') . '</div>';
                } else {
                    if ($tenant->status == TENANT_STATUS_ACTIVE) {
                        $html = ' <div class="status-btn status-btn-green font-13 radius-4">' . __('Active') . '</div>';
                    } elseif ($tenant->status == TENANT_STATUS_INACTIVE) {
                        $html = ' <div class="status-btn status-btn-orange font-13 radius-4">' . __('Deactivate') . '</div>';
                    } elseif ($tenant->status == TENANT_STATUS_DRAFT) {
                        $html = ' <div class="status-btn status-btn-blue font-13 radius-4">' . __('Draft') . '</div>';
                    } elseif ($tenant->status == TENANT_STATUS_CLOSE) {
                        $html = ' <div class="status-btn status-btn-red font-13 radius-4">' . __('Close') . '</div>';
                    }
                }
                return $html;
            })
            ->addColumn('action', function ($tenant) {
                return '<div class="tbl-action-btns d-inline-flex">
                        <a href="' . route('owner.tenant.details', [$tenant->id, 'tab' => 'profile']) . '" class="p-1 tbl-action-btn" title="' . __('Edit') . '"><span class="iconify" data-icon="carbon:view-filled"></span></a>
                    </div>';
            })
            ->rawColumns(['name', 'property', 'status', 'action'])
            ->make(true);
    }

    public function getAllHistoryData()
    {
        $tenants = Tenant::query()
            ->join('users', 'tenants.user_id', '=', 'users.id')
            ->whereNull('users.deleted_at')
            ->leftJoin('properties', 'tenants.property_id', '=', 'properties.id')
            ->leftJoin('property_units', 'tenants.unit_id', '=', 'property_units.id')
            ->select(['tenants.*', 'users.first_name', 'users.last_name', 'users.status as userStatus', 'users.contact_number', 'users.email', 'property_units.unit_name', 'properties.name as property_name'])
            ->where('tenants.owner_user_id', getOwnerUserId());

        return datatables($tenants)
            ->addIndexColumn()
            ->addColumn('name', function ($tenant) {
                return '<div class="tenants-tbl-info-object d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <img src="' . $tenant->image . '"
                            class="rounded-circle avatar-md tbl-user-image"
                            alt="">
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6>' . $tenant->first_name . ' ' . $tenant->last_name . '</h6>
                            <p class="font-13">' . $tenant->email . '</p>
                        </div>
                    </div>';
            })
            ->addColumn('property', function ($tenant) {
                return $tenant->property_name;
            })
            ->addColumn('unit', function ($tenant) {
                return $tenant->unit_name;
            })
            ->addColumn('status', function ($tenant) {
                $html = '';
                if ($tenant->userStatus == USER_STATUS_DELETED) {
                    $html = ' <div class="status-btn status-btn-orange font-13 radius-4">' . __('Deleted') . '</div>';
                } else {
                    if ($tenant->status == TENANT_STATUS_ACTIVE) {
                        $html = ' <div class="status-btn status-btn-green font-13 radius-4">' . __('Active') . '</div>';
                    } elseif ($tenant->status == TENANT_STATUS_INACTIVE) {
                        $html = ' <div class="status-btn status-btn-orange font-13 radius-4">' . __('Inactive') . '</div>';
                    } elseif ($tenant->status == TENANT_STATUS_DRAFT) {
                        $html = ' <div class="status-btn status-btn-blue font-13 radius-4">' . __('Draft') . '</div>';
                    } elseif ($tenant->status == TENANT_STATUS_CLOSE) {
                        $html = ' <div class="status-btn status-btn-red font-13 radius-4">' . __('Close') . '</div>';
                    }
                }
                return $html;
            })
            ->addColumn('action', function ($tenant) {
                return '<div class="tbl-action-btns d-inline-flex">
                        <a href="' . route('owner.tenant.details', [$tenant->id, 'tab' => 'profile']) . '" class="p-1 tbl-action-btn" title="Edit"><span class="iconify" data-icon="carbon:view-filled"></span></a>
                    </div>';
            })
            ->rawColumns(['name', 'property', 'status', 'action'])
            ->make(true);
    }

    public function getById($id)
    {
        $data = Tenant::where('owner_user_id', getOwnerUserId())->findOrFail($id);
        return $data?->makeHidden(['created_at', 'updated_at', 'deleted_at']);
    }

    public function getDetailsById($id)
    {
        if (auth()->user()->role == USER_ROLE_OWNER) {
            $userId = auth()->id();
        } else {
            $userId = auth()->user()->owner_user_id;
        }
        $data = Tenant::query()
            ->join('users', 'tenants.user_id', '=', 'users.id')
            ->whereNull('users.deleted_at')
            ->leftJoin('tenant_details', 'tenants.id', '=', 'tenant_details.tenant_id')
            ->leftJoin('properties', 'tenants.property_id', '=', 'properties.id')
            ->leftJoin('property_details', 'properties.id', '=', 'property_details.property_id')
            ->leftJoin('property_units', 'tenants.unit_id', '=', 'property_units.id')
            ->select(['tenants.*', 'users.first_name', 'users.last_name', 'users.contact_number', 'users.email', 'property_units.unit_name', 'properties.name as property_name', 'property_details.address as property_address', 'tenant_details.previous_address', 'tenant_details.previous_country_id', 'tenant_details.previous_state_id', 'tenant_details.previous_city_id', 'tenant_details.permanent_address', 'tenant_details.permanent_country_id', 'tenant_details.permanent_state_id', 'tenant_details.permanent_city_id'])
            ->where('tenants.owner_user_id', $userId)
            ->where('tenants.id', $id)
            ->firstOrFail();
        return $data?->makeHidden(['created_at', 'updated_at', 'deleted_at']);
    }

    public function closingStatusHistory($id)
    {
        return Tenant::query()->where('owner_user_id', getOwnerUserId())->where('status', TENANT_STATUS_CLOSE)->findOrFail($id);
    }

    public function getPaymentByTenantId($id)
    {
        $data = Invoice::query()
            ->join('tenants', 'invoices.property_unit_id', '=', 'tenants.unit_id')
            ->select('invoices.*')
            ->where('tenants.owner_user_id', getOwnerUserId())
            ->where('tenants.id', $id)
            ->get();
        return $data?->makeHidden(['created_at', 'updated_at', 'deleted_at']);
    }

    public function payment($id)
    {
        $invoices = Invoice::query()
            ->leftJoin('properties', 'invoices.property_id', '=', 'properties.id')
            ->leftJoin('property_units', 'invoices.property_unit_id', '=', 'property_units.id')
            ->select('invoices.*', 'property_units.unit_name', 'properties.name as property_name')
            ->where('invoices.owner_user_id', getOwnerUserId())
            ->where('invoices.tenant_id', $id);

        return datatables($invoices)
            ->addIndexColumn()
            ->addColumn('created_at', function ($invoice) {
                return $invoice->created_at->format('Y-m-d h:m');
            })
            ->addColumn('invoice', function ($invoice) {
                return $invoice->created_at->format('Y') . $invoice->id;
            })
            ->addColumn('amount', function ($invoice) {
                return $invoice->amount ?? 0;
            })
            ->addColumn('status', function ($invoice) {
                if ($invoice->status == INVOICE_STATUS_PAID) {
                    $html =  '<div class="status-btn status-btn-green font-13 radius-4">' . __('Paid') . '</div>';
                } elseif ($invoice->status == INVOICE_STATUS_PENDING) {
                    $html = '<div class="d-flex justify-content-start">';
                    $html .=  '<div class="status-btn status-btn-orange font-13 radius-4">' . __('Unpaid') . '</div>';
                    $html .= '<button type="button" class="p-1 tbl-action-btn payStatus" data-detailsurl="' . route('owner.invoice.details', $invoice->id) . '" title="Payment Status Change"><span class="iconify" data-icon="ic:outline-payments"></span></button>';
                    $html .= '</div>';
                } else {
                    $html = '<div class="d-flex justify-content-start">';
                    $html =  '<div class="status-btn status-btn-red font-13 radius-4">' . __('Due') . '</div>';
                    $html .= '<button type="button" class="p-1 tbl-action-btn payStatus" data-detailsurl="' . route('owner.invoice.details', $invoice->id) . '" title="Payment Status Change"><span class="iconify" data-icon="ic:outline-payments"></span></button>';
                    $html .= '</div>';
                }
                return $html;
            })
            ->rawColumns(['amount', 'created_at', 'status', 'invoice'])
            ->make(true);
    }

    public function paymentDue($id)
    {
        return Invoice::query()
            ->select('invoices.*')
            ->join('tenants', 'invoices.property_unit_id', '=', 'tenants.unit_id')
            ->whereNot('invoices.status', INVOICE_STATUS_PAID)
            ->where('tenants.owner_user_id', getOwnerUserId())
            ->where('tenants.id', $id)
            ->get();
    }

    public function documentDestroy($id)
    {
        $document = FileManager::where('origin_type', 'App\Models\Tenant')->where('id', $id)->first();
        $tenantExists = Tenant::where('id', $document->origin_id)->where('owner_user_id', getOwnerUserId())->exists();
        if (!is_null($document) && $tenantExists) {
            $document->delete();
        } else {
            $message = __("Document not found");
            return $this->error([], $message);
        }
        return $this->success([], __(DELETED_SUCCESSFULLY));
    }

    public function step1(Request $request)
    {
        DB::beginTransaction();
        try {
            $id = $request->get('id', '');
            if ($id != '') {
                $tenant = Tenant::where('owner_user_id', getOwnerUserId())->findOrFail($request->id);
                $user = User::where('owner_user_id', getOwnerUserId())->findOrFail($tenant->user_id);
                $details = TenantDetails::firstOrNew(['tenant_id' => $tenant->id]);
            } else {
                if (!getOwnerLimit(RULES_TENANT) > 0) {
                    throw new Exception(__('Your Tenant Limit finished'));
                }
                $user = new User();
                $tenant = new Tenant();
                $details = new TenantDetails();
            }

            // User
            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name;
            $user->email = $request->email;
            $user->contact_number = $request->contact_number;
            if ($request->password) {
                $user->password = Hash::make($request->password);
            }
            $user->role = USER_ROLE_TENANT;
            $user->status = ACTIVE;
            $user->owner_user_id = getOwnerUserId();
            $user->save();

            // Tenant
            $tenant->user_id = $user->id;
            $tenant->owner_user_id = getOwnerUserId();
            $tenant->job = $request->job;
            $tenant->age = $request->age;
            $tenant->family_member = $request->family_member;
            $tenant->status = TENANT_STATUS_DRAFT;
            $tenant->save();

            // Detail
            $details->tenant_id = $tenant->id;
            $details->permanent_country_id = $request->permanent_country_id;
            $details->permanent_state_id = $request->permanent_state_id;
            $details->permanent_city_id = $request->permanent_city_id;
            $details->permanent_address = $request->permanent_address;
            $details->previous_country_id = $request->previous_country_id;
            $details->previous_state_id = $request->previous_state_id;
            $details->previous_city_id = $request->previous_city_id;
            $details->previous_address = $request->previous_address;
            $details->save();

            /*File Manager Call upload for Thumbnail Image*/
            if ($request->image) {
                $new_file = FileManager::where('origin_type', 'App\Models\Tenant')->where('id', $tenant->image_id)->first();
                if ($new_file) {
                    $new_file->removeFile();
                    $upload = $new_file->updateUpload($new_file->id, 'Tenant', $request->image);
                } else {
                    $new_file = new FileManager();
                    $upload = $new_file->upload('Tenant', $request->image);
                }

                if ($upload['status']) {
                    $tenant->image_id = $upload['file']->id;
                    $tenant->save();

                    $upload['file']->origin_type = "App\Models\Tenant";
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
            $data = $tenant;
            $data->step = 'nextStep1';
            $message = $request->id ? __(UPDATED_SUCCESSFULLY) : __(CREATED_SUCCESSFULLY);
            return $this->success($data, $message);
        } catch (Exception $e) {
            DB::rollBack();
            $message = getErrorMessage($e, $e->getMessage());
            return $this->error([],  $message);
        }
    }

    public function step2(Request $request)
    {
        DB::beginTransaction();
        try {
            $unitExist = Tenant::where('owner_user_id', getOwnerUserId())->where('unit_id', $request->unit_id)->where('status', TENANT_STATUS_ACTIVE)->whereNot('id', $request->id)->first();
            if (!is_null($unitExist)) {
                throw new Exception(__('Unit already Used'));
            }
            $tenant = Tenant::where('owner_user_id', getOwnerUserId())->findOrFail($request->id);
            $tenant->property_id = $request->property_id;
            $tenant->unit_id = $request->unit_id;
            $tenant->lease_start_date = $request->lease_start_date;
            $tenant->lease_end_date = $request->lease_end_date;
            $tenant->general_rent = $request->general_rent;
            $tenant->security_deposit_type = $request->security_deposit_type;
            $tenant->security_deposit = $request->security_deposit;
            $tenant->late_fee_type = $request->late_fee_type;
            $tenant->late_fee = $request->late_fee;
            $tenant->incident_receipt = $request->incident_receipt;
            $tenant->due_date = $request->due_date;
            $tenant->save();

            DB::commit();
            $data = $tenant;
            $data->step = 'nextStep2';
            $message = __(UPDATED_SUCCESSFULLY);
            return $this->success($data, $message);
        } catch (Exception $e) {
            DB::rollBack();
            $message = getErrorMessage($e, $e->getMessage());
            return $this->error([],  $message);
        }
    }

    public function step3(Request $request)
    {
        DB::beginTransaction();
        try {
            $tenant = Tenant::where('owner_user_id', getOwnerUserId())->findOrFail($request->id);
            $tenant->status = TENANT_STATUS_ACTIVE;
            $tenant->save();
            /*File Manager Call upload*/
            if ($request->file('file')) {
                $new_file = new FileManager();
                $upload = $new_file->upload('Tenant', $request->file);

                if ($upload['status']) {
                    $upload['file']->origin_id = $tenant->id;
                    $upload['file']->origin_type = "App\Models\Tenant";
                    $upload['file']->save();
                } else {
                    throw new Exception($upload['message']);
                }
            }
            /*End*/
            DB::commit();
            $data = $tenant;
            $data->step = 'lastStep';;
            $message = __(UPDATED_SUCCESSFULLY);
            return $this->success($data, $message);
        } catch (Exception $e) {
            DB::rollBack();
            $message = getErrorMessage($e, $e->getMessage());
            return $this->error([],  $message);
        }
    }

    public function closeHistoryStore($request, $id)
    {
        DB::beginTransaction();
        try {
            $tenant = Tenant::where('owner_user_id', getOwnerUserId())->findOrFail($id);
            $tenant->status = TENANT_STATUS_CLOSE;
            $tenant->close_refund_amount = $request->close_refund_amount;
            $tenant->close_charge = $request->close_charge;
            $tenant->close_date = $request->close_date;
            $tenant->close_reason = $request->close_reason;
            $tenant->save();

            DB::commit();
            $message = __(UPDATED_SUCCESSFULLY);
            return $this->success([], $message);
        } catch (Exception $e) {
            DB::rollBack();
            $message = getErrorMessage($e, $e->getMessage());
            return $this->error([],  $message);
        }
    }

    public function delete($request)
    {
        DB::beginTransaction();
        try {
            $tenant = Tenant::where('owner_user_id', getOwnerUserId())->findOrFail($request->tenant_id);
            if ($tenant->user->email != $request->email) {
                throw new Exception(__('Tenant Not Found'));
            }
            User::findOrFail($tenant->user_id)->delete();
            TenantDetails::where('tenant_id', $tenant->id)->delete();
            $tenant->delete();
            DB::commit();
            $message = __(DELETED_SUCCESSFULLY);
            return $this->success([], $message);
        } catch (Exception $e) {
            DB::rollBack();
            $message = getErrorMessage($e, $e->getMessage());
            return $this->error([],  $message);
        }
    }
}
