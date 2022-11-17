<?php

namespace App\Actions\User;

use App\Actions\Order\CreateOrder;
use App\Actions\TelegramMessage\CreateTelegramMessage;
use App\Enums\OrderStatus;
use App\Enums\TelegramMessageFrom;
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
        $telegramBotService    = new TelegramBotService();
        $createTelegramMessage = new CreateTelegramMessage();
        $tinkoff               = app(TinkoffPaymentService::class);

        foreach ($subscriptions as $subscription) {
            $subscriptionModel = $item->subscriptions()->where("id", $subscription["id"])->first();
            $updated           = $subscription["updated"] === "1" ? true : false;
            $is_auto_renewal   = isset($subscription["is_auto_renewal"]) ? true : false;
            $bill              = isset($subscription["bill"]) ? true : false;

            if (!$updated) continue;

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
                        "subscription_id" => $subscriptionModel->id,
                        "period_id"       => $period->id,
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
                        "Name"     => $subscriptionModel->title,
                        "Price"    => $order->amount,
                        "NDS"      => "vat20",
                        "Quantity" => 1,
                    ];

                    $payment_url = $tinkoff->paymentURL($payment, $items);

                    if($payment_url) {
                        $text = "Ссылка на оплату услуги: <a href='" . $payment_url . "'>$order->description</a>";

                        if ($telegramBotService->sendMessage(
                            api_token: config("bot.bot_api_token"),
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
                        Log::error($tinkoff->error);
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
