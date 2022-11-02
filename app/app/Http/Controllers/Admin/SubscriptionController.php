<?php

namespace App\Http\Controllers\Admin;

use App\Actions\Subscription\CreateSubscription;
use App\Http\Controllers\Controller;
use App\Models\GraphCategory;
use App\Models\Period;
use App\Models\TelegramChannel;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function create()
    {
        $periods = Period::query()
            ->orderBy("count_name", "ASC")
            ->orderBy("count", "ASC")
            ->get();

        $graphCategories = GraphCategory::query()
            ->withoutParent()
            ->get();

        $telegramChannels = TelegramChannel::query()
            ->get();

        return response()->view("admin.subscription", compact("periods", "graphCategories", "telegramChannels"));
    }

    public function store(Request $request, CreateSubscription $create)
    {
        $create->handle($request->only([
            "title",
            "is_test",
            "periods",
            "content",
            "graph_categories",
            "telegram_channels",
            "period_count_name",
        ]));

        return response()
            ->redirectToRoute("voyager.subscription.index")
            ->with([
                'message'    => "Подписка успешно создана",
                'alert-type' => 'success',
            ]);
    }
}
