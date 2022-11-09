<?php

namespace App\Actions\Subscription;

use App\Models\Period;
use App\Models\Subscription;

class UpdatePeriodsSubscription
{
    public function handle(Subscription $item, array $periods, string $period_count_name): Subscription
    {
        if ($item->is_test) {
            list($period_count, $period_count_name) = explode("-", $period_count_name);

            $period = Period::findByCountName($period_count, $period_count_name);

            $item->periods()->detach();
            $item->periods()->attach($period->id);

            return $item;
        }

        $item->periods()->detach();
        foreach ($periods as $period_data) {
            if ($period_data["price"]) {
                list($period_count, $period_count_name) = explode("-", $period_data["count_name"]);

                $period = Period::findByCountName($period_count, $period_count_name);

                $item->periods()->attach($period->id, ["price" => $period_data["price"]]);
            }
        }

        return $item;
    }
}
