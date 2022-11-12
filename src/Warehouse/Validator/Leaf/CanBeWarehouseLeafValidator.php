<?php

namespace App\Warehouse\Validator\Leaf;

use App\Warehouse\Entity\WarehouseStructureTree;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class CanBeWarehouseLeafValidator extends ConstraintValidator
{
    public const CONTAINS_CHILDREN_MESSAGE = 'Ten element zawiera pod elementy.';

    public function validate(mixed $value, Constraint $constraint)
    {
        if (!$constraint instanceof CanBeWarehouseLeaf) {
            throw new UnexpectedTypeException($constraint, CanBeWarehouseLeaf::class);
        }

        /** @var WarehouseStructureTree $node */
        $node = $value;

        if ($node->getChildren()->count() > 0) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ message }}', self::CONTAINS_CHILDREN_MESSAGE)
                ->addViolation();
        }
    }
}