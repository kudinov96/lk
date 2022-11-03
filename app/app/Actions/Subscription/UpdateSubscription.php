<?php

namespace App\Actions\Subscription;

use App\Models\Period;
use App\Models\Subscription;

class UpdateSubscription
{
    public function handle(Subscription $item, array $data): Subscription
    {
        $item->title   = $data["title"] ?? $item->title;
        $item->content = $data["content"] ?? null;
        $item->is_test = isset($data["is_test"]) ? true : false;

        $item->save();

        if ($item->is_test) {
            list($period_count, $period_count_name) = explode("-", $data["period_count_name"]);

            $period = Period::findByCountName($period_count, $period_count_name);

            $item->periods()->detach();
            $item->periods()->attach($period->id);

            return $item;
        }

        $item->periods()->detach();
        foreach ($data["periods"] as $period_data) {
            if ($period_data["price"]) {
                list($period_count, $period_count_name) = explode("-", $period_data["count_name"]);

                $period = Period::findByCountName($period_count, $period_count_name);

                $item->periods()->attach($period->id, ["price" => $period_data["price"]]);
            }
        }

        $item->graph_categories()->sync($data["graph_categories"]);
        $item->telegram_channels()->sync($data["telegram_channels"]);

        return $item;
    }
}
