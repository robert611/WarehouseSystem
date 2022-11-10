<?php

namespace App\Warehouse\Validator\DTO;

use App\Warehouse\Entity\WarehouseStructureTree;
use App\Warehouse\Validator\Leaf as WarehouseAssert;

class SetNodeAsLeafDTO
{
    #[WarehouseAssert\CanBeWarehouseLeaf]
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
