<?php

namespace App\Services\SmsMail;

use App\Models\EmailTemplate;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Support\Facades\DB;

class EmailTemplateService
{
    use ResponseTrait;

    public function getAllByOwner()
    {
        return EmailTemplate::where('owner_user_id', getOwnerUserId())->select(['id', 'subject', 'body', 'category', 'status'])->get();
    }

    public function store($request)
    {
        DB::beginTransaction();
        try {
            $id = $request->get('id', '');
            if ($id != '') {
                $template = EmailTemplate::where('owner_user_id', getOwnerUserId())->findOrFail($request->id);
                if ($template->category != $request->category || $request->status == ACTIVE) {
                    EmailTemplate::whereNot('id', $request->id)->where('owner_user_id', getOwnerUserId())->where('category', $request->category)->update(['status' => DEACTIVATE]);
                }
            } else {
                $template = EmailTemplate::where('owner_user_id', getOwnerUserId())->where('category', $request->category)->first();
                if ($template) {
                    throw new Exception(__('Email Template Already Exists'));
                }
                $template = new EmailTemplate();
            }
            $template->status = $request->status == ACTIVE ? ACTIVE : DEACTIVATE;
            $template->category = $request->category;
            $template->owner_user_id = getOwnerUserId();
            $template->subject = $request->subject;
            $template->body = $request->body;
            $template->save();
            DB::commit();
            $message = __(CREATED_SUCCESSFULLY);
            return $this->success([], $message);
        } catch (Exception $e) {
            DB::rollBack();
            $message = getErrorMessage($e, $e->getMessage());
            return $this->error([], $message);
        }
    }

    public function details($id)
    {
        try {
            $data = EmailTemplate::where('owner_user_id', getOwnerUserId())->select(['id', 'subject', 'body', 'category', 'status'])->findOrFail($id);
            return $this->success($data);
        } catch (Exception $e) {
            return $this->error([], $e->getMessage());
        }
    }
}
