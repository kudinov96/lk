<?php

namespace App\Http\Controllers\Admin;

use App\Actions\Subscription\CreateSubscription;
use App\Actions\Subscription\UpdateSubscription;
use App\Http\Controllers\Controller;
use App\Models\GraphCategory;
use App\Models\Period;
use App\Models\Subscription;
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

        return response()->view("admin.subscription.create", compact("periods", "graphCategories", "telegramChannels"));
    }

    public function edit(int $id)
    {
        $item = Subscription::findOrFail($id);

        $periods = Period::query()
            ->orderBy("count_name", "ASC")
            ->orderBy("count", "ASC")
            ->get();

        $graphCategories = GraphCategory::query()
            ->withoutParent()
            ->get();

        $telegramChannels = TelegramChannel::query()
            ->get();

        return response()->view("admin.subscription.edit", compact("item", "periods", "graphCategories", "telegramChannels"));
    }

    public function store(Request $request, CreateSubscription $create)
    {
        $create->handle($request->only([
            "title",
            "is_test",
            "periods",
            "content",
            "color",
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

    public function update(int $id, Request $request, UpdateSubscription $update)
    {
        $item = Subscription::findOrFail($id);

        $update->handle($item, $request->only([
            "title",
            "is_test",
            "periods",
            "content",
            "color",
            "graph_categories",
            "telegram_channels",
            "period_count_name",
        ]));

        return response()
            ->redirectToRoute("voyager.subscription.edit", ["id" => $id])
            ->with([
                'message'    => "Подписка успешно отредактирована",
                'alert-type' => 'success',
            ]);
    }
}
