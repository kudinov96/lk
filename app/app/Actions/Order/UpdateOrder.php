<?php

namespace App\Actions\Order;

use App\Models\Order;

class UpdateOrder
{
    public function handel(Order $item, array $data): Order
    {
        $item->description  = $data["description"] ?? $item->description;
        $item->amount       = $data["amount"] ?? $item->amount;
        $item->name         = $data["name"] ?? $item->name;
        $item->email        = $data["email"] ?? $item->email;
        $item->phone        = $data["phone"] ?? $item->phone;
        $item->status       = $data["status"] ?? $item->status;
        $item->user_id      = $data["user_id"] ?? $item->user_id;
        $item->service_id   = $data["service_id"] ?? $item->service_id;
        $item->service_type = $data["service_type"] ?? $item->service_type;
        $item->period_id    = $data["period_id"] ?? $item->period_id;

        $item->save();

        return $item;
    }
}
