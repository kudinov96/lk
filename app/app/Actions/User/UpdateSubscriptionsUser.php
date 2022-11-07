<?php

namespace App\Actions\User;

use App\Models\User;

class UpdateSubscriptionsUser
{
    public function handle(User $item, array $subscriptions): User
    {
        $item->subscriptions()->detach();
        foreach ($subscriptions as $subscription) {
            $added = $subscription["added"] === "1" ? true : false;

            if ($added && $subscription["period"]) {
                list($count , $count_name) = explode("-", $subscription["period"]);

                $item->subscriptions()->attach($subscription["id"], [
                    "date_start" => now(),
                    "date_end"   => now()->add($count, $count_name),
                ]);
            }
        }

        $item->save();

        return $item;
    }
}
