<?php

namespace App\Actions\User;

use App\Models\User;

class DeleteServicesUser
{
    public function handle(User $item, array $services): User
    {
        $item->services()->detach($services);

        return $item;
    }
}
