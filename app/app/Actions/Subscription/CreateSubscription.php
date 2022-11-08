<?php

namespace App\Actions\Subscription;

use App\Models\Period;
use App\Models\Subscription;

class CreateSubscription
{
    public function handle(array $data): Subscription
    {
        $item          = new Subscription();
        $item->title   = $data["title"];
        $item->content = $data["content"] ?? null;
        $item->color   = $data["color"] ?? null;
        $item->is_test = isset($data["is_test"]) ? true : false;

        $item->save();

        if ($item->is_test) {
            list($period_count, $period_count_name) = explode("-", $data["period_count_name"]);

            $period = Period::findByCountName($period_count, $period_count_name);

            $item->periods()->attach($period->id);

            return $item;
        }

        foreach ($data["periods"] as $period_data) {
            if ($period_data["price"]) {
                list($period_count, $period_count_name) = explode("-", $period_data["count_name"]);

                $period = Period::findByCountName($period_count, $period_count_name);

                $item->periods()->attach($period->id, ["price" => $period_data["price"]]);
            }
        }

        if (isset($data["graph_categories"])) {
            $item->graph_categories()->sync($data["graph_categories"]);
        }

        if (isset($data["telegram_channels"])) {
            $item->telegram_channels()->sync($data["telegram_channels"]);
        }

        return $item;
    }
}
