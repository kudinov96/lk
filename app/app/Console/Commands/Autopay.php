<?php

namespace App\Console\Commands;

use App\Models\Subscription;
use App\Models\User;
use App\Services\Payment\TinkoffPaymentService;
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
    public function handle(TinkoffPaymentService $tinkoffPaymentService)
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
                ])->first();
                $cardList     = $tinkoffPaymentService->getCardList($user->id);
                $rebill_id    = end($cardList)->RebillId;
                $payment_id   = $order->payment_id;

                //$tinkoffPaymentService->charge($payment_id, $rebill_id);
            }

        }

        return Command::SUCCESS;
    }
}
