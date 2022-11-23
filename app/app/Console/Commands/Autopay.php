<?php

namespace App\Console\Commands;

use App\Actions\Order\CreateOrder;
use App\Actions\TelegramMessage\CreateTelegramMessage;
use App\Actions\User\UpdateSubscriptionsUser;
use App\Enums\OrderStatus;
use App\Enums\TelegramMessageFrom;
use App\Helpers\PaymentHelper;
use App\Models\Subscription;
use App\Models\User;
use App\Services\Payment\TinkoffPaymentService;
use App\Services\TelegramBotService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class Autopay extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = "x:autopay";

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Autopay command.";

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(TinkoffPaymentService $tinkoffPaymentService, CreateOrder $createOrder, UpdateSubscriptionsUser $updateSubscriptionsUser, TelegramBotService $telegramBotService, CreateTelegramMessage $createTelegramMessage)
    {
        $subscriptions = DB::table("subscription_users")
            ->where([
                ["is_auto_renewal", true],
                ["auto_renewal_try", "<", 3],
            ])
            ->whereDate("date_end", ">=", now())
            ->get();

        foreach ($subscriptions as $subscription) {
            $date_end   = Carbon::parse($subscription->date_end);
            $date_end_3 = Carbon::parse($subscription->date_end)->subDays(3);
            $date_now   = Carbon::now();

            if (($date_end >= $date_now) && ($date_end_3 <= $date_now)) {
                $user         = User::findOrFail($subscription->user_id);
                $order        = $user->orders()->where([
                    ["service_type", Subscription::class],
                    ["service_id", $subscription->subscription_id],
                ])->orderByDesc("created_at")->first();
                $cardList     = $tinkoffPaymentService->getCardList($user->id);
                $rebill_id    = end($cardList)->RebillId;
                $payment_id   = $order->payment_id;

                $paymentResponse = $tinkoffPaymentService->charge($payment_id, $rebill_id);

                if ($paymentResponse && $paymentResponse->Status === OrderStatus::CONFIRMED->value) {
                    $subscriptionModel         = Subscription::findOrFail($subscription->subscription_id);
                    $period                    = $subscriptionModel->periods()
                        ->wherePivot("is_default", true)
                        ->first();

                    list($count , $count_name) = explode("-", $period->full_count_name);
                    $new_date_end = Carbon::make($subscription->date_end)->add($count, $count_name);

                    $createOrder->handle([
                        "description"     => $order->description,
                        "amount"          => (int) $paymentResponse->Amount / 100,
                        "name"            => $order->name,
                        "email"           => $order->email,
                        "phone"           => $order->phone,
                        "status"          => OrderStatus::CONFIRMED->value,
                        "user_id"         => $order->user_id,
                        "service_id"      => $order->service_id,
                        "service_type"    => Subscription::class,
                        "period_id"       => $period->id,
                        "payment_id"      => $order->payment_id,
                        "is_auto_renewal" => true,
                    ]);

                    $updateSubscriptionsUser->handle($user, [
                        [
                            "id"              => $order->service_id,
                            "updated"         => "1",
                            "period"          => $period->full_count_name,
                            "is_auto_renewal" => $order->is_auto_renewal,
                            "new_date_end"    => $new_date_end,
                        ],
                    ]);

                    if ($telegramBotService->sendMessage(
                        chat_id: $user->telegram_id,
                        text: setting("site.bot_autopay"),
                    )) {
                        $createTelegramMessage->handle([
                            "user_id" => $user->id,
                            "text"    => setting("site.bot_autopay"),
                            "from"    => TelegramMessageFrom::BOT->value,
                        ]);
                    }
                }
            }

        }

        return Command::SUCCESS;
    }
}
