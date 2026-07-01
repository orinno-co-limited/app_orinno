@foreach ($users as $user)
    @php
        if(request()->get('receiver_id') == $user->id){
        $isActive = 'active';
        }elseif(request()->get('receiver_id') == NULL && $loop->first){
        $isActive = 'active';
        }else{
        $isActive = '';
        }
    @endphp
    <div class="user-chat {{ $isActive }}" data-id="{{ $user->id }}" data-name="{{ $user->first_name }} {{ $user->last_name}}">
        <div class="user-chat-img">
            <img class="flex-shrink-0" src="{{ $user->image }}" alt="{{ $user->first_name }} {{ $user->last_name}}" />
        </div>
        <div class="user-chat-text-time">
            <div class="user-chat-text">
                <p class="name">{{ $user->first_name }} {{ $user->last_name}}</p>
                <small class="user-last-message-{{ $user->id }}">{{ getSubText($user->last_message, 14) }}</small>
            </div>
            <div class="user-chat-time">
                <p class="time user-last-seen-time-{{ $user->id }}">{{ $user->last_message_time ?
                \Carbon\Carbon::parse($user->last_message_time)->diffForHumans() : '' }}</p>
                <p class="notify user-unseen-message-{{ $user->id }} {{ $user->unseen_message_count >0 ? '' : 'd-none' }}">
                    {{ $user->unseen_message_count }}</p>
            </div>
        </div>
    </div>
@endforeach
