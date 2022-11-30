<?php

namespace App\Actions\User;

use App\Actions\Order\CreateOrder;
use App\Actions\TelegramMessage\CreateTelegramMessage;
use App\Enums\OrderStatus;
use App\Enums\TelegramMessageFrom;
use App\Models\Subscription;
use App\Models\User;
use App\Services\Payment\TinkoffPaymentService;
use App\Services\TelegramBotService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class UpdateSubscriptionsUser
{
    public function handle(User $item, array $subscriptions): User
    {
        $createOrder           = new CreateOrder();
        $createTelegramMessage = new CreateTelegramMessage();
        $telegramBotService    = app(TelegramBotService::class);
        $tinkoffPaymentService = app(TinkoffPaymentService::class);

        foreach ($subscriptions as $subscription) {
            $subscriptionModel = $item->subscriptions()->where("id", $subscription["id"])->first();
            $new_data_end      = isset($subscription["new_date_end"]) ? Carbon::make($subscription["new_date_end"]) : null;
            $updated           = $subscription["updated"] === "1" ? true : false;
            $is_auto_renewal   = isset($subscription["is_auto_renewal"]) ? true : false;
            $bill              = isset($subscription["bill"]) ? true : false;

            if (!$updated) continue;

            if ($new_data_end && $subscriptionModel->date_end !== $new_data_end->format("d.m.Y")) {
                $item->subscriptions()->updateExistingPivot($subscription["id"], [
                    "date_end" => $new_data_end,
                ]);

                continue;
            }

            $updateFields = [];
            if ($subscription["period"]) {
                list($count , $count_name) = explode("-", $subscription["period"]);

                $updateFields["date_end"] = Carbon::parse($subscriptionModel->pivot->date_end)->add($count, $count_name);

                if ($bill) {
                    $period = $subscriptionModel->periods()
                        ->where([
                            ["count", $count],
                            ["count_name", $count_name],
                        ])
                        ->first();
                    $priceAfterDiscount = $period->priceAfterDiscount($subscriptionModel->id);

                    $order = $createOrder->handle([
                        "description"     => $period->fullPaymentDescription($subscriptionModel->id),
                        "amount"          => $priceAfterDiscount["price"],
                        "name"            => $item->fio,
                        "email"           => $item->email,
                        "phone"           => $item->phone,
                        "status"          => OrderStatus::NEW->value,
                        "user_id"         => $item->id,
                        "service_id"      => $subscriptionModel->id,
                        "service_type"    => Subscription::class,
                        "period_id"       => $period->id,
                        "is_auto_renewal" => $is_auto_renewal,
                    ]);

                    $payment = [
                        "OrderId"       => $order->id,
                        "Amount"        => $order->amount,
                        "Language"      => "ru",
                        "Description"   => $order->description,
                        'CustomerKey'   => $item->id,
                        "Email"         => $order->email,
                        "Phone"         => $order->phone,
                        "Name"          => $order->name,
                        "Taxation"      => "usn_income",
                    ];

                    if ($is_auto_renewal) {
                        $payment["Recurrent"] = "Y";
                    }

                    $payment_info = $tinkoffPaymentService->paymentURL($payment, [
                        [
                            "Name"     => $subscriptionModel->title,
                            "Price"    => $order->amount,
                            "NDS"      => "none",
                            "Quantity" => 1,
                        ]
                    ]);

                    if($payment_info["payment_url"]) {
                        $text = "Ссылка на оплату услуги: <a href='" . $payment_info["payment_url"] . "'>$order->description</a>";

                        $order->update([
                            "payment_id" => $payment_info["payment_id"],
                        ]);

                        if ($telegramBotService->sendMessage(
                            chat_id: $item->telegram_id,
                            text: $text,
                        )) {
                            $createTelegramMessage->handle([
                                "user_id" => $item->id,
                                "text"    => $text,
                                "from"    => TelegramMessageFrom::BOT->value,
                            ]);
                        }
                    } else {
                        Log::error($tinkoffPaymentService->error);
                    }
                }
            }

            $updateFields["is_auto_renewal"] = $is_auto_renewal;

            $item->subscriptions()
                ->updateExistingPivot($subscription["id"], $updateFields);
        }

        return $item;
    }
}
