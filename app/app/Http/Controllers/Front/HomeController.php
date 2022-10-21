<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

class HomeController extends Controller
{
    public function index(): Response
    {
        return response()->view("app.home", [
            "sessionId" => session()->getId(),
            "botName"   => config("bot.bot_name"),
        ]);
    }
}
