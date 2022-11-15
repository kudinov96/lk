<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class TelegramBotService
{
    private const TELEGRAM_API_URL = "https://api.telegram.org/bot";

    public function setWebhook(string $api_token, string $url): array
    {
        return Http::get(self::TELEGRAM_API_URL . $api_token . "/setWebhook", [
            "url" => $url,
        ])->json();
    }

    public function getWebhookInfo(string $api_token): array
    {
        return Http::post(self::TELEGRAM_API_URL . $api_token . "/getWebhookInfo")->json();
    }

    public function sendMessage(string $api_token, int $chat_id, string $text): bool
    {
        return Http::post(self::TELEGRAM_API_URL . $api_token . '/sendMessage', [
            'chat_id' => $chat_id,
            'text' => $text,
            'parse_mode' => 'HTML',
        ])->successful();
    }
}
