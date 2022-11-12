<?php

namespace App\Warehouse\Validator\Leaf;

use App\Warehouse\Entity\WarehouseStructureTree;
use App\Warehouse\Repository\WarehouseItemRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class CanBeUnsetFromLeafValidator extends ConstraintValidator
{
    public const CONTAINS_ITEMS_MESSAGE = 'Ten pojemnik zawiera miejsca magazynowe, które nie są puste.';

    public function __construct(private readonly WarehouseItemRepository $warehouseItemRepository)
    {
    }

    public function validate(mixed $value, Constraint $constraint)
    {
        if (!$constraint instanceof CanBeUnsetFromLeaf) {
            throw new UnexpectedTypeException($constraint, CanBeUnsetFromLeaf::class);
        }

        /** @var WarehouseStructureTree $node */
        $node = $value;

        if ($this->warehouseItemRepository->getNotFreeLeafItemsCount($node->getId()) > 0) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ message }}', self::CONTAINS_ITEMS_MESSAGE)
                ->addViolation();
        }
    }
}
