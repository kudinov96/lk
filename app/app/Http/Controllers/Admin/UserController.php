<?php

namespace App\Http\Controllers\Admin;

use App\Actions\User\DeleteUser;
use App\Actions\User\UpdateBanUser;
use App\Actions\User\UpdateCoursesUser;
use App\Actions\User\UpdateDiscountsUser;
use App\Actions\User\UpdateServicesUser;
use App\Actions\User\UpdateSubscriptionsUser;
use App\Models\Course;
use App\Models\GraphCategory;
use App\Models\Service;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use TCG\Voyager\Events\BreadDataAdded;
use TCG\Voyager\Facades\Voyager;
use TCG\Voyager\Http\Controllers\VoyagerUserController;
use TCG\Voyager\Models\Role;

class UserController extends VoyagerUserController
{
    public function index(Request $request)
    {
        $s       = $request->input("s") ?? "";
        $sort_by = $request->input("sort_by") ?? "";

        $users  = User::latest();

        if ($s) {
            $users = $users->whereRaw('LOWER("name") LIKE ? ',['%' . strtolower($s) . '%']);
        }

        if ($sort_by) {
            list($modelName, $id) = explode("-", $sort_by);

            if ($modelName === "subscription") {
                $users = Subscription::findOrFail($id)
                    ->users();
            }

            if ($modelName === "graphCategory") {
                $graphCategory = GraphCategory::findOrFail($id);

                $users = $users->whereHas("subscriptions", function (Builder $query) use ($graphCategory) {
                    $query->whereHas("graph_categories", function (Builder $query) use ($graphCategory) {
                        $query->where("id", $graphCategory->id);
                    });
                });
            }
        }

        $users = $users->paginate(30);

        $subscriptions   = Subscription::all();
        $graphCategories = GraphCategory::query()
            ->withoutParent()
            ->get();

        return response()->view("admin.user.index", compact(
            "users",
            "subscriptions",
            "graphCategories",
        ));
    }

    public function create(Request $request)
    {
        $roles         = Role::all();
        $subscriptions = Subscription::all();
        $services      = Service::all();
        $courses       = Course::all();

        return response()->view("admin.user.create", compact(
            "roles",
            "subscriptions",
            "services",
            "courses",
        ));
    }

    public function store(Request $request)
    {
        $updateSubscriptionsUser = new UpdateSubscriptionsUser();
        $updateServicesUser      = new UpdateServicesUser();
        $updateCoursesUser       = new UpdateCoursesUser();
        $updateDiscountsUser     = new UpdateDiscountsUser();

        $slug = "users";

        $subscriptions = $request->input("subscriptions") ?? [];
        $services      = $request->input("services") ?? [];
        $courses       = $request->input("courses") ?? [];
        $discounts     = $request->input("discounts") ?? [];

        $request->merge([
            "is_ban" => $request->input("is_ban") ? true : false,
        ]);

        $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->first();

        // Check permission
        $this->authorize('add', app($dataType->model_name));

        // Validate fields with ajax
        $val = $this->validateBread($request->all(), $dataType->addRows)->validate();
        $data = $this->insertUpdateData($request, $slug, $dataType->addRows, new User());

        event(new BreadDataAdded($dataType, $data));

        $updateSubscriptionsUser->handle($data, $subscriptions);
        $updateServicesUser->handle($data, $services);
        $updateCoursesUser->handle($data, $courses);
        $updateDiscountsUser->handle($data, $discounts);

        $redirect = redirect()->route("voyager.{$slug}.index");

        return $redirect->with([
            'message'    => __('voyager::generic.successfully_added_new')." {$dataType->getTranslatedAttribute('display_name_singular')}",
            'alert-type' => 'success',
        ]);
    }

    public function update(Request $request, $id)
    {
        dd($request->all());
    }

    public function actions(Request $request, UpdateBanUser $updateBan, DeleteUser $delete): array
    {
        $action = $request->input("action") ?? null;
        $ids    = $request->input("ids") ?? [];
        $users  = User::findOrFail($ids);

        if (strripos($action, "add-subscription") === 0) {
            $subscription_id = explode("-", $action)[2];
            $subscription    = Subscription::findOrFail($subscription_id);

            foreach ($users as $user) {
                if (!$user->subscriptions()->where("id", $subscription->id)->exists()) {
                    $user->subscriptions()->attach($subscription, [
                        "date_start" => now(),
                        "date_end"   => now()->addMonth(),
                    ]);
                }
            }
        }

        if ($action === "clear-subscriptions") {
            foreach ($users as $user) {
                $user->subscriptions()->detach();
            }
        }

        if ($action === "clear-courses") {
            foreach ($users as $user) {
                $user->courses()->detach();
            }
        }

        if ($action === "ban") {
            foreach ($users as $user) {
                $updateBan->handle($user, true);
            }
        }

        if ($action === "delete") {
            foreach ($users as $user) {
                $delete->handle($user);
            }
        }

        return [
            "success" => true,
        ];
    }
}
