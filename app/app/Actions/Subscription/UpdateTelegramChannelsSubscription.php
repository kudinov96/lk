<?php

namespace App\Actions\Subscription;

use App\Models\Subscription;

class UpdateTelegramChannelsSubscription
{
    public function handle(Subscription $item, array $telegram_channels): Subscription
    {
        if (isset($telegram_channels)) {
            $item->telegram_channels()->sync($telegram_channels);
        } else {
            $item->telegram_channels()->detach();
        }

        return $item;
    }
}
