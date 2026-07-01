<?php

namespace App\Services;

use App\Models\TicketTopic;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Support\Facades\DB;

class TicketTopicService
{
    use ResponseTrait;

    public function getAll()
    {
        return TicketTopic::where('owner_user_id', getOwnerUserId())->get();
    }

    public function getActiveAll()
    {
        $topics = TicketTopic::where('owner_user_id', auth()->user()->owner_user_id)->where('status', ACTIVE)->get();
        return $topics?->makeHidden(['created_at', 'updated_at', 'deleted_at', 'owner_user_id', 'status']);
    }

    public function store($request)
    {
        DB::beginTransaction();
        try {
            $id = $request->get('id', '');
            if ($id != '') {
                $ticket = TicketTopic::findOrFail($request->id);
            } else {
                $ticket = new TicketTopic();
            }
            $ticket->name = $request->name;
            $ticket->owner_user_id = getOwnerUserId();
            $ticket->status = $request->status;
            $ticket->save();

            DB::commit();
            $message = $request->id ? __(UPDATED_SUCCESSFULLY) : __(CREATED_SUCCESSFULLY);
            return $this->success([], $message);
        } catch (Exception $e) {
            DB::rollBack();
            $message = getErrorMessage($e, $e->getMessage());
            return $this->error([],  $message);
        }
    }

    public function delete($id)
    {
        try {
            $ticket = TicketTopic::where('owner_user_id', getOwnerUserId())->findOrFail($id);
            $ticket->delete();
            return redirect()->back()->with('success', __(DELETED_SUCCESSFULLY));
        } catch (Exception $e) {
            $message = getErrorMessage($e, $e->getMessage());
            return $this->error([],  $message);
        }
    }
}
