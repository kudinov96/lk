<?php

namespace App\Actions\TelegramMessage;

use App\Models\TelegramMessage;

class UpdateTelegramMessage
{
    public function handle(TelegramMessage $item, array $data): TelegramMessage
    {
        $item->user_id    = $data["user_id"] ?? $item->user_id;
        $item->text       = $data["text"] ?? $item->text;
        $item->from       = $data["from"] ?? $item->from;
        $item->is_read    = $data["is_read"] ?? $item->is_read;
        $item->created_at = $data["created_at"] ?? $item->created_at;
        $item->updated_at = $data["updated_at"] ?? $item->updated_at;

        $item->save();

        return $item;
    }
}
