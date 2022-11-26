<?php

namespace App\Warehouse\Validator\Leaf;

use Attribute;
use Symfony\Component\Validator\Constraint;

#[Attribute]
class LeafCapacityContainsItems extends Constraint
{
    public string $message = 'warehouse_leaf.capacity_contains_items';
    public string $mode = 'strict';

    public function getTargets(): array|string
    {
        return self::CLASS_CONSTRAINT;
    }
}
