<?php

namespace App\Product\Model\Enum;

use App\Shared\Enum\TransformableToString;
use App\Shared\Enum\TranslatableToString;

enum SaleTypeEnum: int implements TransformableToString, TranslatableToString
{
    case BUY_NOW = 1;
    case AUCTION = 2;
    case BOTH = 3;

    public function toString(): string
    {
        return match ($this) {
            self::BUY_NOW => 'Buy Now',
            self::AUCTION => 'Auction',
            self::BOTH => 'Buy Now & Auction',
        };
    }

    public static function translateCaseToString(mixed $case): string
    {
        return match ($case) {
            self::BUY_NOW->value => 'Buy Now',
            self::AUCTION->value => 'Auction',
            self::BOTH->value => 'Buy Now & Auction',
            default => '',
        };
    }
}