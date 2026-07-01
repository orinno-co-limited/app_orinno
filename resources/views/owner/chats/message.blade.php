@extends('owner.layouts.app')
@push('title')
{{ $title }}
@endpush
@section('content')
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                <div class="page-content-wrapper bg-white p-30 radius-20">
                    <div class="content-chat">
                        <div class="content-chat-user">
                            <div class="head-chat">
                                <h4 class="title">{{ __('Chat') }}</h4>
                            </div>
                            <div class="list-search-user-chat" id="chat-user">
                                @include('owner.chats.chat-user-list')
                            </div>
                        </div>
                        <div class="content-chat-message-user-wrap">
                            @foreach ($users as $user)
                                @php
                                    if(request()->get('receiver_id') == $user->id){
                                        $isActive = 'active';
                                    }elseif(request()->get('receiver_id') == NULL &&  $loop->first){
                                        $isActive = 'active';
                                    }else{
                                        $isActive = '';
                                    }
                                @endphp
                                <div class="content-chat-message-user {{ $isActive }}" data-id={{ $user->id }}>
                                    <div class="head-chat-message-user" id="chat-head-{{ $user->id }}">
                                        @include('owner.chats.chat-head')
                                    </div>
                                    <div class="body-chat-message-user" id="chat-body-{{ $user->id }}">
                                    </div>
                                </div>
                            @endforeach
                            <div class="footer-chat-message-user border-0">
                                <div id="files-area">
                                    <span id="filesList">
                                        <span id="files-names"></span>
                                    </span>
                                </div>
                                <form action="{{ route('owner.chats.send_message') }}" enctype="multipart/form-data" id="send-form" data-handler="sendResponse" method="POST">
                                    @csrf
                                    <div class="footer-inputs d-flex justify-content-between g-10">
                                        <input type="hidden" name="receiver_id" id="form-receiver-id" value="">
                                        <div class="message-user-send">
                                            <input type="text" name="message" class="send-message" placeholder="{{ __('Type your message here') }}..." />
                                        </div>
                                        <button type="submit" class="send-btn">
                                            <img src="{{ asset('assets/images/send.svg')}}" alt="" />
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
<input type="hidden" id="single-user-chat-route" value="{{ route('owner.chats.single_user_chat') }}">
@endsection

@push('script')
    <script src="{{ asset('assets/js/custom/chat.js') }}"></script>
@endpush

