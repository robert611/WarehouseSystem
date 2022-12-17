<?php

namespace App\Warehouse\Service\Chart\WarehouseItemStatus;

use App\Warehouse\Model\Enum\WarehouseItemStatusEnum;

trait ChartBackgroundColorTrait
{
    public function getStatusBackgroundColor(string $statusName): string
    {
        $backgroundColors = [
            (WarehouseItemStatusEnum::FREE)->toString() => '#388e3c',
            (WarehouseItemStatusEnum::RESERVED)->toString() => '#0277bd',
            (WarehouseItemStatusEnum::TAKEN)->toString() => '#c62828',
            (WarehouseItemStatusEnum::BLOCKED)->toString() => '#fb8c00',
        ];

        return $backgroundColors[$statusName];
    }
}
