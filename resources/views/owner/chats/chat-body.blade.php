
@foreach ($chats as $chat)
    @php
        $isSender = $chat->sender_id == auth()->id();
        $alignmentClass = $isSender ? 'message-user-right' : 'message-user-left';
        $textClass = $isSender ? 'message-user-right-text' : 'message-user-left-text';
    @endphp

    <div class="{{ $alignmentClass }}">
        <div class="{{ $textClass }}">
            <div class="text">
                @if (!empty($chat->message))
                    <p>{!! nl2br(e($chat->message)) !!}</p>
                @endif

                <div class="time-read">
                    <span class="time">{{ $chat->created_at->diffForHumans() }}</span>
                    <div class="d-flex fill-green">
                        <svg width="17" height="11" viewBox="0 0 17 11" fill="none"
                             xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                  d="M11.6121 1.96514C11.8728 1.64327 11.8232 1.17099 11.5014 0.910273C11.1795 0.649555 10.7072 0.699128 10.4465 1.021L4.48888 8.37597L2.05787 5.96099C1.76401 5.66906 1.28914 5.67063 0.997217 5.96449C0.705293 6.25835 0.706863 6.73322 1.00072 7.02515L4.02062 10.0251C4.1711 10.1746 4.37783 10.2534 4.58963 10.242C4.80143 10.2305 4.99848 10.13 5.13199 9.96514L11.6121 1.96514ZM16.5944 1.98618C16.8667 1.67407 16.8345 1.2003 16.5224 0.927974C16.2103 0.655653 15.7365 0.687909 15.4642 1.00002L9.00668 8.40105L8.55438 7.95757C8.25861 7.66758 7.78376 7.67226 7.49377 7.96802C7.20377 8.26379 7.20845 8.73864 7.50422 9.02863L8.52411 10.0286C8.67155 10.1732 8.87205 10.2506 9.07837 10.2425C9.28469 10.2345 9.47858 10.1418 9.61432 9.98619L16.5944 1.98618Z"
                                  fill="#1B1C17" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endforeach
