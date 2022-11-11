<?php

namespace App\Warehouse\Model\Enum;

use App\Shared\Enum\TransformableToString;

enum WarehouseItemStatusEnum implements TransformableToString
{
    case FREE;
    case RESERVED;
    case TAKEN;
    case BLOCKED;

    public function toString(): string
    {
        return match($this)
        {
            self::FREE => 'FREE',
            self::RESERVED => 'GREEN',
            self::TAKEN => 'TAKEN',
            self::BLOCKED => 'BLOCKED',
        };
    }
}