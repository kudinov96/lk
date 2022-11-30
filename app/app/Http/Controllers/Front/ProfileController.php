<?php

namespace App\Http\Controllers\Front;

use App\Actions\User\UpdateUser;
use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Service;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function profile(): Response
    {
        $user             = auth()->user();
        $subscriptionIcon = $user->subscriptionsActive()->first()->icon ?? "";

        $userSubscriptionsWithoutCategories = $user->subscriptionsActive()->withoutCategories()->get();
        $userSubscriptionsWithCategories    = $user->subscriptionsActive()->withCategories()->get();

        $subscriptionsWithoutCategories = Subscription::withoutCategories()->get();
        $subscriptionsWithCategories = Subscription::withCategories()->get();

        return response()->view("app.user.profile", compact(
            "user",
            "subscriptionIcon",
            "userSubscriptionsWithoutCategories",
            "userSubscriptionsWithCategories",
            "subscriptionsWithoutCategories",
            "subscriptionsWithCategories",
        ));
    }

    public function update(Request $request, int $id, UpdateUser $updateUser): RedirectResponse
    {
        $user = User::findOrFail($id);

        $updateUser->handle($user, [
            "name"          => $request->input("name") ?? null,
            "telegram_name" => $request->input("telegram_name") ?? null,
        ]);

        return redirect()->route("user.profile");
    }

    public function logout(): RedirectResponse
    {
        Auth::logout();

        return redirect()->route("home");
    }

    public function subscriptionsWithoutCategories(): Response
    {
        $user          = auth()->user();
        $subscriptions = Subscription::withoutCategories()->get();

        return response()->view("app.user.subscriptions-without-categories", compact(
            "user",
            "subscriptions",
        ));
    }

    public function subscriptionsWithCategories(): Response
    {
        $user          = auth()->user();
        $subscriptions = Subscription::withCategories()->get();

        return response()->view("app.user.subscriptions-with-categories", compact(
            "user",
            "subscriptions",
        ));
    }

    public function graphs(): Response
    {
        $user                            = auth()->user();
        $userSubscriptionsWithCategories = $user->subscriptionsActive()->withCategories()->get();
        $subscriptionsWithCategories     = Subscription::withCategories()->get();

        return response()->view("app.user.graphs", compact(
            "user",
            "userSubscriptionsWithCategories",
            "subscriptionsWithCategories",
        ));
    }

    public function services(): Response
    {
        $user     = auth()->user();
        $courses  = Course::latest()->get();
        $services = Service::latest()->get();

        return response()->view("app.user.services", compact(
            "user",
            "courses",
            "services",
        ));
    }
}
