<?php

namespace App\Actions\User;

use App\Models\User;

class UpdateUser
{
    public function handle(User $item, array $data): User
    {
        $item->name          = $data["name"] ?? $item->name;
        $item->email         = $data["email"] ?? $item->email;
        $item->telegram_id   = $data["telegram_id"] ?? $item->telegram_id;
        $item->telegram_name = $data["telegram_name"] ?? $item->telegram_name;
        $item->is_ban        = $data["is_ban"] ?? $item->is_ban;
        $item->fio           = $data["fio"] ?? $item->fio;
        $item->phone         = $data["phone"] ?? $item->phone;

        $item->save();

        return  $item;
    }
}
