<?php

namespace App\Services;

use App\Models\FileManager;
use App\Models\Ticket;
use App\Models\TicketReply;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class TicketService
{
    use ResponseTrait;
    public function getAll()
    {
        $data = Ticket::query()
            ->where('owner_user_id', getOwnerUserId())
            ->with('topic')
            ->get();
        return $data?->makeHidden(['created_at', 'updated_at', 'deleted_at']);
    }

    public function getAllData()
    {
        $tickets = Ticket::query()
            ->where('owner_user_id', getOwnerUserId())
            ->with('topic');
        return datatables($tickets)
            ->addIndexColumn()
            ->addColumn('ticket', function ($ticket) {
                return '#' . $ticket->ticket_no;
            })
            ->addColumn('title', function ($ticket) {
                return Str::limit($ticket->title, 25, '...');
            })
            ->addColumn('details', function ($ticket) {
                return Str::limit($ticket->title, 40, '...');
            })
            ->addColumn('status', function ($ticket) {
                $html = '';
                if ($ticket->status == TICKET_STATUS_OPEN) {
                    $html = '<p class="status-btn status-btn-orange">' . __('Open') . '</p>';
                } elseif ($ticket->status == TICKET_STATUS_INPROGRESS) {
                    $html = '<p class="status-btn status-btn-blue">' . __('Inprogress') . '</p>';
                } elseif ($ticket->status == TICKET_STATUS_REOPEN) {
                    $html = '<p class="status-btn status-btn-red">' . __('Reopen') . '</p>';
                } elseif ($ticket->status == TICKET_STATUS_RESOLVED) {
                    $html = '<p class="status-btn status-btn-green">' . __('Resolved') . '</p>';
                } else {
                    $html = '<p class="status-btn status-btn-red">' . __('Close') . '</p>';
                }
                return $html;
            })
            ->addColumn('action', function ($ticket) {
                return '<div class="tbl-action-btns d-inline-flex">
                            <a href="' . route('owner.ticket.details', $ticket->id) . '" class="p-1 tbl-action-btn" title="' . __('View') . '"><span class="iconify" data-icon="carbon:view-filled"></span></a>
                            <div class="ticket-item-dropdown text-end ms-2 mt-1">
                                <div class="dropdown">
                                    <a class="dropdown-toggle dropdown-toggle-nocaret"
                                        href="#" data-bs-toggle="dropdown"
                                        aria-expanded="false">
                                        <i class="ri-more-2-fill"></i>
                                    </a>
                                    <ul
                                        class="dropdown-menu">
                                        <li><a class="dropdown-item font-13 statusChange"
                                                data-url="' . route('owner.ticket.status.change') . '"
                                                data-id="' . $ticket->id . '" data-status="2"
                                                href="javascript:;"
                                                title="' . __('In Processing') . '">' . __('In Processing') . '</a>
                                        </li>
                                        <li><a class="dropdown-item font-13 statusChange"
                                                data-url="' . route('owner.ticket.status.change') . '"
                                                data-id="' . $ticket->id . '" data-status="3"
                                                href="javascript:;"
                                                title="' . __('Close') . '">' . __('Close') . '</a>
                                        </li>
                                        <li><a class="dropdown-item font-13 statusChange"
                                                data-url="' . route('owner.ticket.status.change') . '"
                                                data-id="' . $ticket->id . '" data-status="5"
                                                href="javascript:;"
                                                title="' . __('Resolved') . '">' . __('Resolved') . '</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>';
            })
            ->rawColumns(['ticket', 'title', 'details', 'status', 'action'])
            ->make(true);
    }

    public function getAllByPropertyId($id)
    {
        $tickets = Ticket::query()
            ->whereIn('property_id', $id)
            ->get();
        foreach ($tickets as $ticket) {
            foreach ($ticket->attachments as $attachment) {
                $attachment->image = assetUrl($attachment->folder_name . '/' . $attachment->file_name);
            }
        }
        return $tickets?->makeHidden(['created_at', 'updated_at', 'deleted_at']);
    }

    public function getAllByUnitId($id)
    {
        $tickets = Ticket::query()
            ->where('unit_id', $id)
            ->get();
        foreach ($tickets as $ticket) {
            foreach ($ticket->attachments as $attachment) {
                $attachment->image = assetUrl($attachment->folder_name . '/' . $attachment->file_name);
            }
        }
        return $tickets?->makeHidden(['created_at', 'updated_at', 'deleted_at', 'owner_user_id']);
    }

    public function searchByUnitId($request, $unit_id)
    {
        return Ticket::query()
            ->where(function ($q) use ($request) {
                $q->where('title', 'like', "%{$request->search}%")
                    ->orWhere('details', 'like', "%{$request->search}%");
            })
            ->when($request->status, function ($q) use ($request) {
                $q->where('status', $request->status);
            })
            ->where('unit_id', $unit_id)
            ->get();
    }

    public function getById($id)
    {
        $user = auth()->user();
        $ownerUserId = auth()->user()->role == USER_ROLE_OWNER ? auth()->id() : auth()->user()->owner_user_id;
        $ticket = Ticket::query()
            ->join('ticket_topics', 'tickets.topic_id', '=', 'ticket_topics.id')
            ->join('properties', 'tickets.property_id', '=', 'properties.id')
            ->join('property_units', 'tickets.unit_id', '=', 'property_units.id')
            ->join('users', 'tickets.user_id', '=', 'users.id')
            ->when($user->role == USER_ROLE_TENANT, function ($q) use ($user) {
                $q->where('tickets.unit_id', $user->tenant->unit_id);
            })
            ->when($user->role == USER_ROLE_MAINTAINER, function ($q) use ($user) {
                $q->whereIn('tickets.property_id', $user->maintainer->properties->pluck('id')->toArray());
            })
            ->where('tickets.owner_user_id', $ownerUserId)
            ->select(
                'tickets.*',
                'ticket_topics.name as topic_name',
                'properties.name as property_name',
                'property_units.unit_name',
                'users.first_name',
                'users.last_name'
            )
            ->findOrFail($id);
        foreach ($ticket->attachments as $attachment) {
            $attachment->image = assetUrl($attachment->folder_name . '/' . $attachment->file_name);
        }
        return $ticket?->makeHidden(['created_at', 'updated_at', 'deleted_at', 'owner_user_id']);
    }

    public function getRepliesByTicketId($id)
    {
        $replies = TicketReply::query()
            ->join('users', 'ticket_replies.user_id', '=', 'users.id')
            ->select('ticket_replies.*', 'users.first_name', 'users.last_name', 'users.role')
            ->where('ticket_replies.ticket_id', $id)
            ->get();
        foreach ($replies as $reply) {
            foreach ($reply->attachments as $attachment) {
                $attachment->image = assetUrl($attachment->folder_name . '/' . $attachment->file_name);
            }
        }
        return $replies?->makeHidden(['created_at', 'updated_at', 'deleted_at']);
    }

    public function store($request)
    {
        DB::beginTransaction();
        try {
            $id = $request->get('id', '');
            if ($id != '') {
                $ticket = Ticket::findOrFail($request->id);
            } else {
                $ticket = new Ticket();
            }
            $user = auth()->user();
            if ($user->role == USER_ROLE_TENANT) {
                $propertyId = $user->tenant->property_id;
                $unitId = $user->tenant->unit_id;
            } else {
                $propertyId = $request->property_id;
                $unitId = $request->unit_id;
            }
            $ticket->user_id = $user->id;
            $ticket->owner_user_id = $user->owner_user_id;
            $ticket->property_id = $propertyId;
            $ticket->unit_id = $unitId;
            $ticket->title = $request->title;
            $ticket->details = $request->details;
            $ticket->topic_id = $request->topic_id;
            $ticket->save();

            if ($request->hasFile('attachments')) {
                foreach ($request->attachments as $key => $attachment) {
                    $newFile = new FileManager();
                    $upload = $newFile->upload('Ticket', $attachment);
                    if ($upload['status']) {
                        $upload['file']->origin_id = $ticket->id;
                        $upload['file']->origin_type = "App\Models\Ticket";
                        $upload['file']->save();
                    } else {
                        throw new Exception($upload['message']);
                    }
                }
            }
            DB::commit();
            $message = $request->id ? __(UPDATED_SUCCESSFULLY) : __(CREATED_SUCCESSFULLY);
            return $this->success([], $message);
        } catch (Exception $e) {
            DB::rollBack();
            $message = getErrorMessage($e, $e->getMessage());
            return $this->error([], $message);
        }
    }

    public function reply($request)
    {
        DB::beginTransaction();
        try {
            $ownerUserId = auth()->user()->role == USER_ROLE_OWNER ? auth()->id() : auth()->user()->owner_user_id;
            $ticket = Ticket::where('owner_user_id', $ownerUserId)->findOrFail($request->ticket_id);

            if (in_array($ticket->status, [TICKET_STATUS_CLOSE, TICKET_STATUS_RESOLVED])) {
                $ticket->status = TICKET_STATUS_REOPEN;
                $ticket->save();
            }

            $reply = TicketReply::create([
                'ticket_id' => $ticket->id,
                'user_id' => auth()->id(),
                'reply' => $request->reply
            ]);

            if ($request->hasFile('attachments')) {
                foreach ($request->attachments as $key => $attachment) {
                    $newFile = new FileManager();
                    $upload = $newFile->upload('TicketReply', $attachment);
                    if ($upload['status']) {
                        $upload['file']->origin_id = $reply->id;
                        $upload['file']->origin_type = "App\Models\TicketReply";
                        $upload['file']->save();
                    } else {
                        throw new Exception($upload['message']);
                    }
                }
            }
            DB::commit();
            $message = __(SENT_SUCCESSFULLY);
            return $this->success([], $message);
        } catch (Exception $e) {
            DB::rollBack();
            $message = getErrorMessage($e, $e->getMessage());
            return $this->error([], $message);
        }
    }

    public function statusChange($request)
    {
        DB::beginTransaction();
        try {
            $ownerUserId = auth()->user()->role == USER_ROLE_OWNER ? auth()->id() : auth()->user()->owner_user_id;
            $ticket = Ticket::where('owner_user_id', $ownerUserId)->findOrFail($request->id);
            $ticket->status = $request->status;
            $ticket->save();
            DB::commit();
            $message = __(STATUS_UPDATED_SUCCESSFULLY);
            return $this->success([], $message);
        } catch (Exception $e) {
            DB::rollBack();
            $message = getErrorMessage($e, $e->getMessage());
            return $this->error([], $message);
        }
    }


    public function delete($id)
    {
        try {
            $ticket = Ticket::where('owner_user_id', auth()->user()->owner_user_id)->findOrFail($id);
            $ticket->delete();
            return redirect()->back()->with('success', __(DELETED_SUCCESSFULLY));
        } catch (Exception $e) {
            return redirect()->back()->with('error', __($e->getMessage()));
        }
    }

    public function deleteById($id)
    {
        try {
            $ticket = Ticket::where('owner_user_id', auth()->user()->owner_user_id)->findOrFail($id);
            $ticket->delete();
            return $this->success([], __(DELETED_SUCCESSFULLY));
        } catch (Exception $e) {
            return $this->error([], __($e->getMessage()));
        }
    }
}
