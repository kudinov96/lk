<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function fullDescription(Request $request)
    {
        $service_type = $request->input("service_type");

        $service      = app($service_type)::findOrFail($request->input("service_id"));

        if ($service_type === Subscription::class) {
            $period = $service->periods()
                ->where("id", $request->input("period_id"))
                ->first();
            $data = $period->fullPaymentDescription($service->id);
        } else {
            $data = "Заказ услуги \"$service->title\": " . $service->price_after_personal_discount["price"] . " руб. (скидка " . $service->price_after_personal_discount["discount"] . "%)";
        }

        return [
            "success" => true,
            "data" => $data,
        ];
    }
}
