<?php

namespace App\Http\Controllers\Front;

use App\Actions\Order\UpdateOrder;
use App\Actions\User\CreateSubscriptionsUser;
use App\Actions\User\UpdateSubscriptionsUser;
use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Period;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PaymentTinkoffController extends Controller
{
    public function callback(Request $request): void
    {
        if ($request->Status === OrderStatus::CONFIRMED->value) {
            $this->statusConfirmed($request);
        }
    }

    public function success(Request $request): Response
    {
        $this->statusConfirmed($request);

        return response()->view("app.user.payment.success");
    }

    public function fail(Request $request, UpdateOrder $updateOrder): Response
    {
        $order = Order::findOrFail($request->OrderId);

        $updateOrder->handel($order, [
            "status" => OrderStatus::REJECTED->value,
        ]);

        return response()->view("app.user.payment.fail");
    }

    private function statusConfirmed(Request $request)
    {
        $updateOrder         = new UpdateOrder();
        $createSubscriptions = new CreateSubscriptionsUser();
        $updateSubscriptions = new UpdateSubscriptionsUser();

        $order  = Order::findOrFail($request->OrderId);
        $period = Period::findOrFail($order->period_id);
        $user   = $order->user;

        $updateOrder->handel($order, [
            "status" => OrderStatus::CONFIRMED->value,
        ]);

        if ($user->subscriptions()->where("id", $order->subscription_id)->exists()) {
            $updateSubscriptions->handle($user, [
                [
                    "id"      => $order->subscription_id,
                    "updated" => "1",
                    "period"  => $period->full_count_name,
                ],
            ]);
        } else {
            $createSubscriptions->handle($user, [
                [
                    "id"     => $order->subscription_id,
                    "added"  => "1",
                    "period" => $period->full_count_name,
                ],
            ]);
        }
    }
}
