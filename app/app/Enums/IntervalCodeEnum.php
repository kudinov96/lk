<?php

namespace App\Enums;

enum IntervalCodeEnum: string
{
    use BaseEnum;

    case MIN   = "MIN";
    case HOUR  = "HOUR";
    case DAY   = "DAY";
    case WEEK  = "WEEK";
    case MONTH = "MONTH";
}
