<?php

namespace App\Actions\TelegramMessage;

use App\Models\TelegramMessage;

class CreateTelegramMessage
{
    public function handle(array $data): TelegramMessage
    {
        $item             = new TelegramMessage();
        $item->user_id    = $data["user_id"];
        $item->text       = $data["text"];
        $item->from       = $data["from"];
        $item->is_read    = $data["is_read"] ?? false;
        $item->created_at = $data["created_at"] ?? now();
        $item->updated_at = $data["updated_at"] ?? now();

        $item->save();

        return $item;
    }
}
