<?php

namespace App\Enums;

enum ActiveStatusEnum: string
{
    case ACTIVE = 'active';
    case INACTIVE = 'inactive';

    public static function values(): array
    {
        return array_map(fn($item) => $item->value, self::cases());
    }
}
