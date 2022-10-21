<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Service\TelegramBotService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class BotController extends Controller
{
    public function setWebhook(TelegramBotService $telegramBotService): array
    {
        $webhook_url = route("bot.webhook");

        return $telegramBotService->setWebhook(config("bot.bot_api_token"), $webhook_url);
    }

    public function getWebhookInfo(TelegramBotService $telegramBotService): array
    {
        return $telegramBotService->getWebhookInfo(config("bot.bot_api_token"));
    }

    public function webhook(Request $request)
    {
        $message            = $request->input("message.text");
        $session_id         = str_replace("/start auth", "", $message);
        $user_telegram_name = $request->input("message.from.username");
        $user_telegram_id   = $request->input("message.from.id");
        $user_firstname     = $request->input("message.from.firstname");
        $user_lastname      = $request->input("message.from.lastname");
        $user               = User::query()->where("telegram_id", $user_telegram_id)->first();

        if ($user) {
            Auth::login($user);
            Session::setId($session_id);
            $user->sessions()->where("id", "!=", $session_id)->delete();
        } else {
            $new_user = User::query()->create([
                "name"          => $user_firstname . " " . $user_lastname,
                "email"         => Str::random(15) . "@" . Str::random(5) . ".com",
                "password"      => Hash::make(Str::random(20)),
                "telegram_id"   => $user_telegram_id,
                "telegram_name" => $user_telegram_name,
            ]);

            Auth::login($new_user);
            Session::setId($session_id);
        }
    }
}
