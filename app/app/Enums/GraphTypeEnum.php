<?php

namespace App\Enums;

enum GraphTypeEnum: string
{
    use BaseEnum;

    case CATEGORY    = "category";
    case SUBCATEGORY = "subcategory";
    case TOOL        = "tool";
}
