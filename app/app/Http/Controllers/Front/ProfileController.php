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
        $user              = auth()->user();
        $subscription_icon = $user->subscriptions()->first()->icon ?? "";

        return response()->view("app.user.profile", compact(
            "user",
            "subscription_icon",
        ));
    }

    public function logout(): RedirectResponse
    {
        Auth::logout();

        return redirect()->route("home");
    }

    public function graphs(): Response
    {
        $user = auth()->user();

        return response()->view("app.user.graphs", compact(
            "user",
        ));
    }

    public function subscriptions(): Response
    {
        $user          = auth()->user();
        $subscriptions = Subscription::latest()->get();

        return response()->view("app.user.subscriptions", compact(
            "user",
            "subscriptions",
        ));
    }
}
