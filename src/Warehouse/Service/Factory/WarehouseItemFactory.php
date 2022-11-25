<?php

namespace App\Warehouse\Service\Factory;

use App\Warehouse\Entity\WarehouseItem;
use App\Warehouse\Entity\WarehouseStructureTree;
use App\Warehouse\Model\Enum\WarehouseItemStatusEnum;

class WarehouseItemFactory
{
    public static function createItem(WarehouseStructureTree $node, int $position): WarehouseItem
    {
        $warehouseItem = new WarehouseItem();
        $warehouseItem->setIdentifier(self::makeIdentifier($node->getTreePath(), $position));
        $warehouseItem->setStatus((WarehouseItemStatusEnum::FREE)->toString());
        $warehouseItem->setProduct(null);
        $warehouseItem->setNode($node);
        $warehouseItem->setPosition($position);

        return $warehouseItem;
    }

    public static function makeIdentifier(string $treePath, int $position): string
    {
        $treePath = str_replace('-', '', $treePath);

        if ($position < 10) {
            $position = '00' . $position;
        }  elseif ($position < 100) {
            $position = '0' . $position;
        }

        return $treePath . $position;
    }
}