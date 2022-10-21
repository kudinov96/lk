<?php

namespace App\Http\Controllers;

use App\Service\TelegramBotService;
use Illuminate\Support\Facades\Log;

class BotController extends Controller
{
    public function setWebhook(TelegramBotService $telegramBotService): string
    {
        $webhook_url = "https://de85-95-182-11-187.eu.ngrok.io/webhook";
        $botApiToken = config('bot.bot_api_token');

        try {
            $telegramBotService->setWebhook($botApiToken, $webhook_url);
        } catch (\Exception $e) {
            dd($e->getMessage());
        }

        return $telegramBotService->getWebhookInfoUrl($botApiToken);
    }

    public function webhook()
    {
        Log::info("Hook is working");
    }
}
