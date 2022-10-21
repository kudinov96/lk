<?php

namespace App\Service;

use Illuminate\Support\Facades\Http;

class TelegramBotService
{
    private const TELEGRAM_API_URL = 'https://api.telegram.org/bot';

    public function setWebhook(string $api_token, string $url): bool
    {
        return Http::get(self::TELEGRAM_API_URL . $api_token . '/setWebhook', [
            'url' => $url,
        ])->ok();
    }

    public function getWebhookInfoUrl(string $api_token): string
    {
        $webhook_info = Http::post(self::TELEGRAM_API_URL . $api_token . '/getWebhookInfo')->json();

        return $webhook_info['ok'] ? $webhook_info['result']['url'] : '';
    }
}
