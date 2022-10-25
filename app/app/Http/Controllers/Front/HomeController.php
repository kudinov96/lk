<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Tool;
use Illuminate\Http\Response;

class HomeController extends Controller
{
    public function index(): Response
    {
        /*Tool::query()->where("title", "Tool1")->update([
            "data" => [
                "MIN" => [
                    "url" => "https://www.google.com/1",
                    "interval" => 15,
                ],
                "HOUR" => [
                    "url" => "https://www.google.com/2",
                    "interval" => 1,
                ],
                "DAY" => [
                    "url" => "https://www.google.com/3",
                    "interval" => 1,
                ],
                "WEEK" => [
                    "url" => "https://www.google.com/4",
                    "interval" => 1,
                ],
                "MONTH" => [
                    "url" => "https://www.google.com/5",
                    "interval" => 1,
                ],
            ],
        ]);*/
        return response()->view("app.home", [
            "sessionId" => session()->getId(),
            "botName"   => config("bot.bot_name"),
        ]);
    }
}
