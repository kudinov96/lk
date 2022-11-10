<?php

namespace App\Http\Controllers\Admin;

use App\Actions\Subscription\CreatePeriodsSubscription;
use App\Actions\Subscription\CreateGraphCategoriesSubscription;
use App\Actions\Subscription\UpdateGraphCategoriesSubscription;
use App\Actions\Subscription\UpdatePeriodsSubscription;
use App\Actions\Subscription\CreateTelegramChannelsSubscription;
use App\Actions\Subscription\UpdateTelegramChannelsSubscription;
use App\Models\GraphCategory;
use App\Models\Period;
use App\Models\Subscription;
use App\Models\TelegramChannel;
use Illuminate\Http\Request;
use TCG\Voyager\Events\BreadDataAdded;
use TCG\Voyager\Facades\Voyager;
use TCG\Voyager\Http\Controllers\VoyagerBaseController;

class SubscriptionController extends VoyagerBaseController
{
    public function create(Request $request)
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

    public function edit(Request $request, $id)
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

    public function store(Request $request)
    {
        $slug = "subscription";

        $createPeriods          = new CreatePeriodsSubscription();
        $createGraphCategories  = new CreateGraphCategoriesSubscription();
        $createTelegramChannels = new CreateTelegramChannelsSubscription();

        $periods           = $request->input("periods") ?? [];
        $period_count_name = $request->input("period_count_name") ?? "";
        $graph_categories  = $request->input("graph_categories") ?? [];
        $telegram_channels = $request->input("telegram_channels") ?? [];

        $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->first();

        $request->merge([
            "is_test" => $request->input("is_test") ? true : false,
        ]);

        // Check permission
        $this->authorize('add', app($dataType->model_name));

        // Validate fields with ajax
        $val = $this->validateBread($request->all(), $dataType->addRows)->validate();
        $data = $this->insertUpdateData($request, $slug, $dataType->addRows, new $dataType->model_name());

        event(new BreadDataAdded($dataType, $data));

        $createPeriods->handle($data, $periods, $period_count_name);
        $createGraphCategories->handle($data, $graph_categories);
        $createTelegramChannels->handle($data, $telegram_channels);

        $redirect = redirect()->route("voyager.{$slug}.index");

        return $redirect->with([
            'message'    => __('voyager::generic.successfully_added_new')." {$dataType->getTranslatedAttribute('display_name_singular')}",
            'alert-type' => 'success',
        ]);
    }

    public function update(Request $request, $id)
    {
        $item              = Subscription::findOrFail($id);

        $periods           = $request->input("periods") ?? [];
        $period_count_name = $request->input("period_count_name") ?? "";
        $graph_categories  = $request->input("graph_categories") ?? [];
        $telegram_channels = $request->input("telegram_channels") ?? [];

        $updatePeriods          = new UpdatePeriodsSubscription();
        $updateGraphCategories  = new UpdateGraphCategoriesSubscription();
        $updateTelegramChannels = new UpdateTelegramChannelsSubscription();

        $updatePeriods->handle($item, $periods, $period_count_name);
        $updateGraphCategories->handle($item, $graph_categories);
        $updateTelegramChannels->handle($item, $telegram_channels);

        $request->merge([
            "is_test" => $request->input("is_test") ? true : false,
        ]);

        return parent::update($request, $id);
    }

    public function periods(Request $request): array
    {
        $subscription = Subscription::findOrFail($request->input("id"));

        $data = [];
        foreach ($subscription->periods as $period) {
            $data[] = [
                "id"   => $period->full_count_name,
                "text" => $period->full_count_name_human,
            ];
        }

        return [
            "success" => true,
            "data" => $data,
        ];
    }
}
