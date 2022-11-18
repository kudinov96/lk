<?php

namespace App\Http\Controllers\Front;

use App\Actions\Order\CreateOrder;
use App\Actions\User\UpdateUser;
use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Services\Payment\TinkoffPaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    public function store(Request $request, CreateOrder $createOrder, TinkoffPaymentService $tinkoff, UpdateUser $updateUser)
    {
        $user         = auth()->user();
        $service_type = $request->input("service_type");
        $service      = app($service_type)::findOrFail($request->input("service_id"));

        if ($service_type === Subscription::class) {
            $period = $service->periods()->where("id", $request->input("period_id"))->first();
            $amount = $period->priceAfterDiscount($service->id)["price"];
        } else {
            $amount = $service->price_discount;
        }

        $updateUser->handle($user, [
            "fio"   => $request->input("name"),
            "email" => $request->input("email"),
            "phone" => $request->input("phone"),
        ]);

        $order = $createOrder->handle([
            "description"     => $request->input("description"),
            "amount"          => $amount,
            "name"            => $request->input("name"),
            "email"           => $request->input("email"),
            "phone"           => $request->input("phone"),
            "status"          => OrderStatus::NEW->value,
            "user_id"         => $user->id,
            "service_id"      => $request->input("service_id"),
            "service_type"    => $request->input("service_type"),
            "period_id"       => $request->input("period_id"),
        ]);

        $payment = [
            "OrderId"       => $order->id,
            "Amount"        => $order->amount,
            "Language"      => "ru",
            "Description"   => $order->description,
            "Email"         => $order->email,
            "Phone"         => $order->phone,
            "Name"          => $order->name,
            "Taxation"      => "usn_income",
        ];

        $items[] = [
            "Name"     => $service->title,
            "Price"    => $order->amount,
            "NDS"      => "vat20",
            "Quantity" => 1,
        ];

        $payment_url = $tinkoff->paymentURL($payment, $items);

        if(!$payment_url) {
            Log::error($tinkoff->error);

            return [
                "success" => true,
                "error"   => $tinkoff->error,
            ];
        }

        return [
            "success"     => true,
            "payment_url" => $payment_url
        ];
    }
}
