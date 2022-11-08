<?php

namespace App\Actions\User;

use App\Models\User;

class CreateServicesUser
{
    public function handle(User $item, array $services): User
    {
        foreach ($services as $service) {
            $added = $service["added"] === "1" ? true : false;

            if ($added) {
                $item->services()->attach($service["id"]);
            }
        }

        $item->save();

        return $item;
    }
}
