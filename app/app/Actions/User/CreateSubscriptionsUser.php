<?php

namespace App\Actions\User;

use App\Models\User;

class CreateSubscriptionsUser
{
    public function handle(User $item, array $subscriptions): User
    {
        foreach ($subscriptions as $subscription) {
            $added           = $subscription["added"] === "1" ? true : false;
            $is_auto_renewal = isset($subscription["is_auto_renewal"]) ? true : false;

            if ($added && $subscription["period"]) {
                list($count , $count_name) = explode("-", $subscription["period"]);

                $item->subscriptions()->attach($subscription["id"], [
                    "date_start"      => now(),
                    "date_end"        => now()->add($count, $count_name),
                    "is_auto_renewal" => $is_auto_renewal,
                ]);
            }
        }

        return $item;
    }
}
