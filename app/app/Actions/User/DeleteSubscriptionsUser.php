<?php

namespace App\Actions\User;

use App\Models\User;

class DeleteSubscriptionsUser
{
    public function handle(User $item, array $subscriptions): User
    {
        $item->subscriptions()->detach($subscriptions);

        return $item;
    }
}
