@if($message->from === \App\Enums\TelegramMessageFrom::BOT)
    <p class="telegram-chat__message" data-id="{{ $message->id }}"><span class="telegram-chat__bot">BOT ({{ $message->created_at->format("H:i d.m.Y") }}):</span> {{ strip_tags($message->text) }}</p>
@endif
@if($message->from === \App\Enums\TelegramMessageFrom::USER)
    <p class="telegram-chat__message" data-id="{{ $message->id }}"><span class="telegram-chat__user">{{ $user->name }} ({{ $message->created_at->format("H:i d.m.Y") }}):</span> {{ strip_tags($message->text) }}</p>
@endif
