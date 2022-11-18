<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;

class HomeController extends Controller
{
    public function index(): Response | RedirectResponse
    {
        if (auth()->user()) {
            return response()->redirectToRoute("user.graphs");
        }

        return response()->view("app.home", [
            "sessionId" => session()->getId(),
            "botName"   => config("bot.bot_name"),
        ]);
    }
}
