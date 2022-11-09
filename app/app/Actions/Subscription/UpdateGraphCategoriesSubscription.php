<?php

namespace App\Actions\Subscription;

use App\Models\Subscription;

class UpdateGraphCategoriesSubscription
{
    public function handle(Subscription $item, array $graph_categories): Subscription
    {
        if (isset($graph_categories)) {
            $item->graph_categories()->sync($graph_categories);
        } else {
            $item->graph_categories()->detach();
        }

        return $item;
    }
}
