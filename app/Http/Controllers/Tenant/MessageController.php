<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\ChatService;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class MessageController extends Controller
{
    use ResponseTrait;
    public $chatService;

    public function __construct()
    {
        $this->chatService = new ChatService();
    }

    public function index(){
        $data['title']= __('Message');
        $data['activeMessage'] = 'active';

        $data['users'] = User::select('users.id', 'users.first_name','users.last_name','file_managers.folder_name','file_managers.file_name')
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
            ->leftJoin('tenants', 'tenants.owner_user_id', '=', 'users.id')
            ->where('tenants.user_id', auth()->id())
            ->groupBy('users.id', 'users.first_name','users.last_name')
            ->where('users.role','=', USER_ROLE_OWNER)
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
        return view('tenant.chats.message', $data);
    }

    public function send(Request $request)
    {
        return  $this->chatService->store($request);
    }

    public function getSingleChat(Request $request)
    {
        $senderId = auth()->id();
        $receiverId = $request->receiver_id;

        $data['chats'] = $this->chatService->getSingleUserChat($senderId, $receiverId);
        $response['unseen_user_message'] = collect($this->chatService->unseenUserMessage()->getData()->data);
        $response['total_unseen_message'] = $response['unseen_user_message']->sum('unseen_message_count');
        $response['html'] = View::make('tenant.chats.chat-body', $data)->render();
        return $this->success($response);
    }

}
