<?php

namespace App\Actions\User;

use App\Models\User;

class UpdateBanUser
{
    public function handle(User $item, bool $is_ban): User
    {
        $item->is_ban = $is_ban;
        $item->save();

        return $item;
    }
}
