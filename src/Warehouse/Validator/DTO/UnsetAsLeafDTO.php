<?php

namespace App\Warehouse\Validator\DTO;

use App\Warehouse\Entity\WarehouseStructureTree;
use App\Warehouse\Validator\Leaf as WarehouseAssert;

class UnsetAsLeafDTO
{
    #[WarehouseAssert\CanBeUnsetFromLeaf]
    private readonly WarehouseStructureTree $node;

    public function __construct(WarehouseStructureTree $node)
    {
        $this->node = $node;
    }

    public function getNode(): WarehouseStructureTree
    {
        return $this->node;
    }
}