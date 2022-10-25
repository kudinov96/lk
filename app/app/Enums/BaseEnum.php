<?php

namespace App\Enums;

trait BaseEnum
{
    public static function getArray(): array
    {
        $array = [];
        foreach (self::cases() as $item) {
            $array[$item->value] = $item->value;
        }

        return $array;
    }
}
