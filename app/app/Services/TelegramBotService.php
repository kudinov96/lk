<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class TelegramBotService
{
    private const TELEGRAM_API_URL = "https://api.telegram.org/bot";
    private string $api_token;

    public function __construct(string $api_token)
    {
        $this->api_token = $api_token;
    }

    public function setWebhook(string $url): array
    {
        return Http::timeout(2)->get($this->generateLink("setWebhook"), [
            "url" => $url,
        ])->json();
    }

    public function getWebhookInfo(): array
    {
        return Http::timeout(2)->post($this->generateLink("getWebhookInfo"))->json();
    }

    public function sendMessage(int $chat_id, string $text): bool
    {
        $this->last_response = Http::timeout(2)->post($this->generateLink("sendMessage"), [
            'chat_id' => $chat_id,
            'text' => $text,
            'parse_mode' => 'HTML',
        ])->json();
		return !empty($this->last_response) && !empty($this->last_response['ok']);
    }

    public function getUserProfilePhotos(int $user_id, int $limit = 1): array
    {
        return Http::timeout(2)->post($this->generateLink("getUserProfilePhotos"), [
            'user_id' => $user_id,
            'limit'   => $limit,
        ])->json();
    }

    public function getFile(string $file_id): array
    {
        return Http::post($this->generateLink("getFile"), [
            'file_id' => $file_id,
        ])->json();
    }

    public function generateFilePath(string $file_path): string
    {
        return "https://api.telegram.org/file/bot{$this->api_token}/{$file_path}";
    }

    private function generateLink(string $method_name): string
    {
        return self::TELEGRAM_API_URL . $this->api_token . "/" . $method_name;
    }
}
