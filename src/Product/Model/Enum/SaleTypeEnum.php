<?php

namespace App\Product\Model\Enum;

enum SaleTypeEnum: int {
    case BUY_NOW = 1;
    case AUCTION = 2;
    case BOTH = 3;
}