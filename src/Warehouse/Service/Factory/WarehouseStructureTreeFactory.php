<?php

namespace App\Warehouse\Service\Factory;

use App\Warehouse\Entity\WarehouseStructureTree;

class WarehouseStructureTreeFactory
{
    public static function createNode(
        string $name,
        bool $isLeaf,
        null|WarehouseStructureTree $parent
    ): WarehouseStructureTree {
        $warehouseStructureTree = new WarehouseStructureTree();
        $warehouseStructureTree->setName(self::makeName($name));
        $warehouseStructureTree->setIsLeaf($isLeaf);
        $warehouseStructureTree->setTreePath(
            self::makeTreePath(
                $warehouseStructureTree->getName(),
                $parent?->getTreePath()
            )
        );
        $warehouseStructureTree->setParent($parent);

        return $warehouseStructureTree;
    }

    public static function makeName(string $name): string
    {
        return strtoupper($name);
    }

    public static function makeTreePath(string $name, null|string $parentTreePath): string
    {
        if ($parentTreePath) {
            return $parentTreePath . '-' . $name;
        }

        return $name;
    }
}