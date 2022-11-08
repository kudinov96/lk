<?php

namespace App\Actions\User;

use App\Models\User;

class DeleteDiscountsUser
{
    public function handle(User $item, array $discounts): User
    {
        foreach ($discounts as $discount) {
            $item->discounts()->where("id", $discount)->delete();
        }

        return $item;
    }
}
