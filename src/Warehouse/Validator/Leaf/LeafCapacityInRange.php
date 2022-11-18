<?php

namespace App\Warehouse\Validator\Leaf;

use Attribute;
use Symfony\Component\Validator\Constraint;

#[Attribute]
class LeafCapacityInRange extends Constraint
{
    public string $message = 'warehouse.leaf.capacity';
    public string $mode = 'strict';
}
