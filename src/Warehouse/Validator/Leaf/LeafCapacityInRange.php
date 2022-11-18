<?php

namespace App\Warehouse\Validator\Leaf;

use Attribute;
use Symfony\Component\Validator\Constraint;

#[Attribute]
class LeafCapacityInRange extends Constraint
{
    public string $message = 'validator.warehouse_leaf_capacity_message';
    public string $mode = 'strict';
}
