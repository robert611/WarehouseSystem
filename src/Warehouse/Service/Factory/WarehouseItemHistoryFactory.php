<?php

namespace App\Warehouse\Service\Factory;

use App\Security\Entity\User;
use App\Warehouse\Entity\WarehouseItem;
use App\Warehouse\Entity\WarehouseItemHistory;
use App\Warehouse\Model\Enum\WarehouseItemStatusEnum;
use Symfony\Component\Security\Core\User\UserInterface;

class WarehouseItemHistoryFactory
{
    public static function createItemHistory(
        WarehouseItem $warehouseItem,
        UserInterface|User $user,
        null|WarehouseItemStatusEnum $customStatus = null
    ): WarehouseItemHistory {
        $warehouseItemHistory = new WarehouseItemHistory();
        $warehouseItemHistory->setIdentifier($warehouseItem->getIdentifier());
        $warehouseItemHistory->setStatus($customStatus ?? $warehouseItem->getStatus());
        $warehouseItemHistory->setProduct($warehouseItem->getProduct());
        $warehouseItemHistory->setUser($user);

        return $warehouseItemHistory;
    }
}
