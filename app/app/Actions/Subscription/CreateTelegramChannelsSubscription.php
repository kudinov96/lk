<?php

namespace App\Actions\Subscription;

use App\Models\Subscription;

class CreateTelegramChannelsSubscription
{
    public function handle(Subscription $item, array $telegram_channels): Subscription
    {
        $item->telegram_channels()->sync($telegram_channels);

        return $item;
    }
}
