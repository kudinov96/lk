<?php

namespace App\Http\Controllers\Front;

use App\Actions\Order\UpdateOrder;
use App\Actions\User\CreateCoursesUser;
use App\Actions\User\CreateServicesUser;
use App\Actions\User\CreateSubscriptionsUser;
use App\Actions\User\UpdateSubscriptionsUser;
use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Order;
use App\Models\Period;
use App\Models\Service;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class PaymentTinkoffController extends Controller
{
    public function callback(Request $request): void
    {
        Log::channel("payments")->info("CALLBACK: " . print_r($request->all(), true));

        if ($request->Status === OrderStatus::CONFIRMED->value) {
            $this->statusConfirmed($request);
        }
    }

    public function success(Request $request): Response
    {
        Log::channel("payments")->info("SUCCESS: " . print_r($request->all(), true));

        $this->statusConfirmed($request);

        return response()->view("app.user.payment.success");
    }

    public function fail(Request $request, UpdateOrder $updateOrder): Response
    {
        Log::channel("payments")->info("FAIL: " . print_r($request->all(), true));

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
        $createServicesUser  = new CreateServicesUser();
        $createCoursesUser   = new CreateCoursesUser();

        $order  = Order::findOrFail($request->OrderId);
        $user   = $order->user;

        $updateOrder->handel($order, [
            "status" => OrderStatus::CONFIRMED->value,
        ]);

        if ($order->service_type === Subscription::class) {
            $period = Period::findOrFail($order->period_id);

            if ($user->subscriptionsActive()->where("id", $order->service_id)->exists()) {
                $updateSubscriptions->handle($user, [
                    [
                        "id"              => $order->service_id,
                        "updated"         => "1",
                        "period"          => $period->full_count_name,
                        "is_auto_renewal" => $order->is_auto_renewal,
                    ],
                ]);
            } else {
                $createSubscriptions->handle($user, [
                    [
                        "id"              => $order->service_id,
                        "added"           => "1",
                        "period"          => $period->full_count_name,
                        "is_auto_renewal" => $order->is_auto_renewal,
                    ],
                ]);
            }
        }

        if ($order->service_type === Service::class) {
            $createServicesUser->handle($user, [
                [
                    "id"     => $order->service_id,
                    "added"  => "1",
                ],
            ]);
        }

        if ($order->service_type === Course::class) {
            $createCoursesUser->handle($user, [
                [
                    "id"     => $order->service_id,
                    "added"  => "1",
                ],
            ]);
        }
    }
}
