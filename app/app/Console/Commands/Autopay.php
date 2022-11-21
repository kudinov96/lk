<?php

namespace App\Console\Commands;

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
            ->whereNotNull("payment_id")
            ->whereDate("date_end", ">=", now())
            ->get();

        foreach ($subscriptions as $subscription) {
            $date_end   = Carbon::parse($subscription->date_end);
            $date_end_3 = Carbon::parse($subscription->date_end)->subDays(3);
            $date_now   = Carbon::now();

            if (($date_end >= $date_now) && ($date_end_3 <= $date_now)) {
                $user_id    = $subscription->user_id;
                $cardList   = $tinkoffPaymentService->getCardList($user_id);
                $rebill_id  = end($cardList)->RebillId;
                $payment_id = $subscription["payment_id"];

                //$tinkoffPaymentService->charge($payment_id, $rebillId);
            }

        }

        return Command::SUCCESS;
    }
}
