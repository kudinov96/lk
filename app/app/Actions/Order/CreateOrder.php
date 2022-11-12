<?php

namespace App\Actions\Order;

use App\Models\Order;

class CreateOrder
{
    public function handle(array $data): Order
    {
        $item                  = new Order();
        $item->description     = $data["description"];
        $item->amount          = $data["amount"];
        $item->name            = $data["name"];
        $item->email           = $data["email"];
        $item->phone           = $data["phone"];
        $item->user_id         = $data["user_id"];
        $item->subscription_id = $data["subscription_id"];
        $item->period_id       = $data["period_id"];
        $item->status          = $data["status"];

        $item->save();

        return $item;
    }
}
