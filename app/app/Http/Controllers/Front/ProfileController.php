<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function profile(): Response
    {
        $user             = auth()->user();
        $subscriptionIcon = $user->subscriptions()->first()->icon ?? "";

        $subscriptionsWithoutCategories = $user->subscriptions()->withoutCategories()->get();
        $subscriptionsWithCategories    = $user->subscriptions()->withCategories()->get();

        return response()->view("app.user.profile", compact(
            "user",
            "subscriptionIcon",
            "subscriptionsWithoutCategories",
            "subscriptionsWithCategories",
        ));
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
        $user = auth()->user();

        return response()->view("app.user.graphs", compact(
            "user",
        ));
    }
}
