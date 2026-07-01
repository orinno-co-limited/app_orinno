<?php

namespace App\Services;

use App\Models\Chat;
use App\Models\User;
use App\Traits\ResponseTrait;
use Illuminate\Support\Facades\DB;
use Exception;

class ChatService
{
    use ResponseTrait;

    public function store($request)
    {
        $validated = $request->validate([
            'message' => 'required',
            'receiver_id' => 'required|exists:users,id',
        ]);
        DB::beginTransaction();
        try {
            $user = auth()->user();
            $chat = new Chat();
            $chat->message = htmlspecialchars($request->message);
            $chat->sender_id = $user->id;
            $chat->receiver_id = $request->receiver_id;
            $chat->save();

            DB::commit();

            $message = __('Send Successfully');
            return $this->success(['receiver_id' => $request->receiver_id], $message);
        } catch (Exception $e) {
            DB::rollBack();
            $message = getErrorMessage($e, $e->getMessage());
            return $this->error([], $message);
        }
    }

    public function getSingleUserChat($senderId, $receiverId)
    {

        //get data
        $chats = Chat::where(function ($query) use ($senderId, $receiverId) {
            $query->where('sender_id', $senderId)->where('receiver_id', $receiverId);
        })->orWhere(function ($query) use ($senderId, $receiverId) {
            $query->where('receiver_id', $senderId)->where('sender_id', $receiverId);
        })->orderBy('chats.created_at')->get();

        //make seen
        Chat::where('sender_id', $receiverId)->update(['is_seen' => 1]);

        return $chats;
    }

    public function getChatUserList()
    {
        $users = User::select('users.id', 'users.first_name','users.last_name','file_managers.folder_name','file_managers.file_name')
            ->leftJoin('chats AS c1', function ($join) {
                $join->on('users.id', '=', 'c1.sender_id');
                $join->whereRaw('c1.id = (SELECT MAX(id) FROM chats WHERE sender_id = users.id and receiver_id = '.auth()->id().')');
            })
            ->leftJoin('chats AS c2', function ($join) {
                $join->on('users.id', '=', 'c2.receiver_id');
                $join->whereRaw('c2.id = (SELECT MAX(id) FROM chats WHERE receiver_id = users.id and sender_id = '.auth()->id().')');
            })
            ->leftJoin('file_managers', function ($join) {
                $join->on('file_managers.origin_id', '=', 'users.id')
                    ->where('file_managers.origin_type', '=', User::class);
            })
            ->selectRaw('CASE
                    WHEN MAX(c1.created_at) >= MAX(c2.created_at) THEN c1.message
                    ELSE c2.message
                END AS last_message')
            ->selectRaw('CASE
                    WHEN MAX(c1.created_at) >= MAX(c2.created_at) THEN MAX(c1.created_at)
                    ELSE MAX(c2.created_at)
                END AS last_message_time')
            ->groupBy('users.id', 'users.first_name','users.last_name')
            ->where('role','=', USER_ROLE_TENANT)
            ->where('users.owner_user_id', auth()->id())
            ->where('users.id', '!=', auth()->id())
            ->where('users.status', 1)
            ->withCount('unseen_message')
            ->get()
            ->map(function ($user) {
                $user->image = ($user->folder_name && $user->file_name)
                    ? asset($user->folder_name . '/' . $user->file_name)
                    : asset('assets/images/no-image.jpg');
                return $user;
            });

        return $this->success($users);
    }

    public function unseenUserMessage()
    {
        $users = User::where('users.role', USER_ROLE_TENANT)
            ->where('users.id', '!=', auth()->id())->leftJoin('chats', ['chats.sender_id' => 'users.id', 'chats.receiver_id' => DB::raw(auth()->id())])
            ->select( 'users.first_name',
                'users.last_name', 'users.id', 'users.last_seen', DB::raw('max(chats.created_at) as last_message_time'), 'chats.message as last_message')
            ->withCount('unseen_message')
            ->groupBy('users.id')->get();

        return $this->success($users);
    }
}
