<?php

namespace App\Actions\User;

use App\Models\User;
use Carbon\Carbon;

class UpdateSubscriptionsUser
{
    public function handle(User $item, array $subscriptions): User
    {
        foreach ($subscriptions as $subscription) {
            $subscriptionModel = $item->subscriptions()->where("id", $subscription["id"])->first();
            $updated           = $subscription["updated"] === "1" ? true : false;
            $is_auto_renewal   = isset($subscription["is_auto_renewal"]) ? true : false;

            if (!$updated) continue;

            $updateFields = [];
            if ($subscription["period"]) {
                list($count , $count_name) = explode("-", $subscription["period"]);

                $updateFields["date_end"] = Carbon::parse($subscriptionModel->pivot->date_end)->add($count, $count_name);
            }

            $updateFields["is_auto_renewal"] = $is_auto_renewal;

            $item->subscriptions()
                ->updateExistingPivot($subscription["id"], $updateFields);
        }

        return $item;
    }
}
