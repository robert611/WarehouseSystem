<?php

namespace App\Warehouse\Model\Enum;

use App\Shared\Enum\EnumToArray;
use App\Shared\Enum\TransformableToString;

enum WarehouseItemStatusEnum implements TransformableToString
{
    use EnumToArray;

    case FREE;
    case RESERVED;
    case TAKEN;
    case BLOCKED;

    public function toString(): string
    {
        return match($this)
        {
            self::FREE => 'FREE',
            self::RESERVED => 'RESERVED',
            self::TAKEN => 'TAKEN',
            self::BLOCKED => 'BLOCKED',
        };
    }
}
