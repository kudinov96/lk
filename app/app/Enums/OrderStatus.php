<?php

namespace App\Enums;

enum OrderStatus: string
{
    use BaseEnum;

    case NEW       = "NEW";
    case CONFIRMED = "CONFIRMED";
    case REJECTED  = "REJECTED";
}
