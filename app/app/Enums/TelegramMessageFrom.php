<?php

namespace App\Enums;

enum TelegramMessageFrom: string
{
    case USER = "user";
    case BOT  = "bot";
}
