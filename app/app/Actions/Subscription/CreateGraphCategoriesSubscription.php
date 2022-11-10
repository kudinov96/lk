<?php

namespace App\Actions\Subscription;

use App\Models\Subscription;

class CreateGraphCategoriesSubscription
{
    public function handle(Subscription $item, array $graph_categories): Subscription
    {
        $item->graph_categories()->sync($graph_categories);

        return $item;
    }
}
