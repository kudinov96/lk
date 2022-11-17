<?php

namespace App\Http\Controllers;

use App\Actions\TelegramMessage\CreateTelegramMessage;
use App\Actions\User\UpdateUser;
use App\Enums\TelegramMessageFrom;
use App\Models\User;
use App\Services\TelegramBotService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BotController extends Controller
{
    public function setWebhook(TelegramBotService $telegramBotService): array
    {
        return $telegramBotService->setWebhook(route("bot.webhook"));
    }

    public function getWebhookInfo(TelegramBotService $telegramBotService): array
    {
        return $telegramBotService->getWebhookInfo();
    }

    public function webhook(Request $request, TelegramBotService $telegramBotService, CreateTelegramMessage $createTelegramMessage)
    {
        $message          = $request->input("message.text");
        $chat_id          = $request->input("message.chat.id");
        $user_telegram_id = $request->input("message.from.id");
        $user             = User::query()->where("telegram_id", $user_telegram_id)->first();

        if ($chat_id !== $user_telegram_id)  {
            return;
        }

        if (strripos($message, "/start auth") === 0) {
            $this->authMessage($request, $telegramBotService, $createTelegramMessage);
        } elseif ($user) {
            $this->message($request, $user, $createTelegramMessage);
        }
    }

    private function authMessage(Request $request, TelegramBotService $telegramBotService, CreateTelegramMessage $createTelegramMessage)
    {
        $message            = $request->input("message.text");
        $chat_id            = $request->input("message.chat.id");
        $session_id         = str_replace("/start auth", "", $message);
        $user_telegram_id   = $request->input("message.from.id");
        $user_telegram_name = $request->input("message.from.username");
        $user_firstname     = $request->input("message.from.first_name") ?? "";
        $user_lastname      = $request->input("message.from.last_name") ?? "";

        $user               = User::query()->where("telegram_id", $user_telegram_id)->first();

        if ($user) {
            if (!$user->telegram_id) {
                $updateUser = new UpdateUser();
                $updateUser->handle($user, ["telegram_id" => $user_telegram_id]);
            }

            Auth::login($user);
            Session::setId($session_id);
            $user->sessions()->where("id", "!=", $session_id)->delete();
        } else {
            $user = User::query()->create([
                "name"          => $user_firstname . " " . $user_lastname,
                "email"         => Str::random(15) . "@" . Str::random(5) . ".com",
                "password"      => Hash::make(Str::random(20)),
                "telegram_id"   => $user_telegram_id,
                "telegram_name" => $user_telegram_name,
                "avatar"        => $avatar_path ?? null,
            ]);

            $this->storeAvatar($user, $telegramBotService);

            Auth::login($user);
            Session::setId($session_id);
        }

        $text = "Вы успешно авторизованы, ждем Вас в <a href=\"" . route("user.profile") . "\">личном кабинете</a>.
Для повторной авторизации снова нажмите кнопку \"Запустить\".";

        if ($telegramBotService->sendMessage(
            chat_id: $chat_id,
            text: $text,
        )) {
            $createTelegramMessage->handle([
                "user_id" => $user->id,
                "text"    => $text,
                "from"    => TelegramMessageFrom::BOT->value,
            ]);
        }
    }

    private function message(Request $request, User $user, CreateTelegramMessage $createTelegramMessage)
    {
        $createTelegramMessage->handle([
            "user_id"    => $user->id,
            "text"       => $request->input("message.text"),
            "from"       => TelegramMessageFrom::USER->value,
            "created_at" => $request->input("message.date"),
            "updated_at" => $request->input("message.date"),
        ]);
    }

    private function storeAvatar(User $user, TelegramBotService $telegramBotService)
    {
        $photos = $telegramBotService->getUserProfilePhotos(
            user_id: $user->telegram_id,
        );

        if (!empty($photos["result"]["photos"])) {
            $photo = $telegramBotService->getFile(
                file_id: $photos["result"]["photos"][0][0]["file_id"],
            );

            $photo_path  = $telegramBotService->generateFilePath($photo["result"]["file_path"]);
            $avatar_path = "users/user-profile-photo-" . $user->id . ".jpg";
            Storage::disk(config("voyager.storage.disk"))->put($avatar_path, file_get_contents($photo_path));

            $updateUser = new UpdateUser();
            $updateUser->handle($user, ["avatar" => $avatar_path]);
        }
    }
}
