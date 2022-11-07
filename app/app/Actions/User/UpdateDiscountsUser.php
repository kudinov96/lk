<?php

namespace App\Actions\User;

use App\Models\Discount;
use App\Models\User;

class UpdateDiscountsUser
{
    public function handle(User $item, array $discounts): User
    {
        $item->discounts()->delete();

        $appends_discounts = [];
        foreach ($discounts as $discount) {
            $added           = $discount["added"] === "1" ? true : false;

            if ($added) {
                list($type, $id) = explode("-", $discount["id"]);
                $type            = "App\Model\\" . $type;

                $appends_discounts[] = new Discount([
                    "count"        => $discount["count"],
                    "user_id"      => $item->id,
                    "service_type" => $type,
                    "service_id"   => $id,
                ]);
            }
        }

        $item->discounts()->saveMany($appends_discounts);

        $item->save();

        return $item;
    }
}
