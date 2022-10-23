<?php

namespace App\Product\Model\Dictionary;

class SaleTypeDictionary
{
    public static function translateToString(int $saleType): string
    {
        $translations = [
            1 => 'BUY_NOW',
            2 => 'AUCTION',
            3 => 'BOTH',
        ];

        return $translations[$saleType];
    }
}