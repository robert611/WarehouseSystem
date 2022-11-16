<?php

namespace App\Warehouse\Validator\Leaf;

use Attribute;
use Symfony\Component\Validator\Constraint;

#[Attribute]
class CanBeWarehouseLeaf extends Constraint
{
    public string $message = '{{ message }}';
    public string $mode = 'strict';
}
