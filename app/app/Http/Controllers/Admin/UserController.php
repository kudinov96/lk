<?php

namespace App\Http\Controllers\Admin;

use App\Actions\User\CreateCoursesUser;
use App\Actions\User\CreateDiscountsUser;
use App\Actions\User\CreateServicesUser;
use App\Actions\User\CreateSubscriptionsUser;
use App\Actions\User\DeleteCoursesUser;
use App\Actions\User\DeleteDiscountsUser;
use App\Actions\User\DeleteServicesUser;
use App\Actions\User\DeleteSubscriptionsUser;
use App\Actions\User\DeleteUser;
use App\Actions\User\UpdateBanUser;
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
        $roles         = Role::latest()->get();
        $subscriptions = Subscription::latest()->get();
        $services      = Service::latest()->get();
        $courses       = Course::latest()->get();

        return response()->view("admin.user.create", compact(
            "roles",
            "subscriptions",
            "services",
            "courses",
        ));
    }

    public function edit(Request $request, $id)
    {
        $item = User::findOrFail($id);

        $roles         = Role::latest()->get();
        $subscriptions = Subscription::latest()->get();
        $services      = Service::latest()->get();
        $courses       = Course::latest()->get();

        return response()->view("admin.user.edit", compact(
            "item",
            "roles",
            "subscriptions",
            "services",
            "courses",
        ));
    }

    public function store(Request $request)
    {
        $slug = "users";

        $createSubscriptionsUser = new CreateSubscriptionsUser();
        $createServicesUser      = new CreateServicesUser();
        $createCoursesUser       = new CreateCoursesUser();
        $createDiscountsUser     = new CreateDiscountsUser();

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

        $createSubscriptionsUser->handle($data, $subscriptions);
        $createServicesUser->handle($data, $services);
        $createCoursesUser->handle($data, $courses);
        $createDiscountsUser->handle($data, $discounts);

        $redirect = redirect()->route("voyager.{$slug}.index");

        return $redirect->with([
            'message'    => __('voyager::generic.successfully_added_new')." {$dataType->getTranslatedAttribute('display_name_singular')}",
            'alert-type' => 'success',
        ]);
    }

    public function update(Request $request, $id)
    {
        $item                = User::findOrFail($id);
        $deleteSubscriptions = $request->input("delete_subscriptions") ?? [];
        $deleteCourses       = $request->input("delete_courses") ?? [];
        $deleteServices      = $request->input("delete_services") ?? [];
        $deleteDiscounts     = $request->input("delete_discounts") ?? [];
        $subscriptions       = $request->input("subscriptions") ?? [];
        $updateSubscriptions = $request->input("update_subscriptions") ?? [];
        $services            = $request->input("services") ?? [];
        $courses             = $request->input("courses") ?? [];
        $discounts           = $request->input("discounts") ?? [];

        $deleteSubscriptionsUser = new DeleteSubscriptionsUser();
        $deleteServicesUser      = new DeleteServicesUser();
        $deleteCoursesUser       = new DeleteCoursesUser();
        $deleteDiscountsUser     = new DeleteDiscountsUser();
        $createSubscriptionsUser = new CreateSubscriptionsUser();
        $updateSubscriptionsUser = new UpdateSubscriptionsUser();
        $createServicesUser      = new CreateServicesUser();
        $createCoursesUser       = new CreateCoursesUser();
        $createDiscountsUser     = new CreateDiscountsUser();

        $deleteSubscriptionsUser->handle($item, $deleteSubscriptions);
        $deleteServicesUser->handle($item, $deleteServices);
        $deleteCoursesUser->handle($item, $deleteCourses);
        $deleteDiscountsUser->handle($item, $deleteDiscounts);
        $createSubscriptionsUser->handle($item, $subscriptions);
        $updateSubscriptionsUser->handle($item, $updateSubscriptions);
        $createServicesUser->handle($item, $services);
        $createCoursesUser->handle($item, $courses);
        $createDiscountsUser->handle($item, $discounts);

        $request->merge([
            "is_ban" => $request->input("is_ban") ? true : false,
        ]);

        return parent::update($request, $id);
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
