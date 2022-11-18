<?php

namespace App\Actions\Order;

use App\Models\Order;

class CreateOrder
{
    public function handle(array $data): Order
    {
        $item               = new Order();
        $item->description  = $data["description"];
        $item->amount       = $data["amount"];
        $item->name         = $data["name"];
        $item->email        = $data["email"];
        $item->phone        = $data["phone"];
        $item->status       = $data["status"];
        $item->user_id      = $data["user_id"];
        $item->service_id   = $data["service_id"];
        $item->service_type = $data["service_type"];
        $item->period_id    = $data["period_id"];

        $item->save();

        return $item;
    }
}
