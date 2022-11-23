<?php

namespace App\Helpers;

use App\Models\Subscription;

class PaymentHelper
{
    public static function fullDescription(string $service_type, int $service_id, ?int $period_id = null): array
    {
        $service = app($service_type)::findOrFail($service_id);

        if ($service_type === Subscription::class) {
            $period = $service->periods()
                ->where("id", $period_id)
                ->first();
            $data = $period->fullPaymentDescription($service->id);
        } else {
            $data = "Заказ услуги \"$service->title\": " . $service->price_after_personal_discount["price"] . " руб.";
            if ($service->price_after_personal_discount["discount"]) {
                $data .= " (скидка " . $service->price_after_personal_discount["discount"] . "%)";
            }

        }

        return [
            "success" => true,
            "data" => $data,
        ];
    }
}
