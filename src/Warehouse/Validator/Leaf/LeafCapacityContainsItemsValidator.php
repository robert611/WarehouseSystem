<?php

namespace App\Warehouse\Validator\Leaf;

use App\Warehouse\Entity\WarehouseLeafSettings;
use App\Warehouse\Repository\WarehouseItemRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class LeafCapacityContainsItemsValidator extends ConstraintValidator
{
    private readonly WarehouseItemRepository $warehouseItemRepository;

    public function __construct(WarehouseItemRepository $warehouseItemRepository)
    {
        $this->warehouseItemRepository = $warehouseItemRepository;
    }

    public function validate(mixed $value, Constraint $constraint)
    {
        if (!$constraint instanceof LeafCapacityContainsItems) {
            throw new UnexpectedTypeException($constraint, LeafCapacityContainsItems::class);
        }

        /** @var WarehouseLeafSettings $leafSettings */
        $leafSettings = $value;

        /* Leaf settings has not been created yet */
        if ($leafSettings->getNode() === null) {
            return;
        }

        $lastNotFreeItemPosition = $this->warehouseItemRepository->getLastNotFreeItemPosition(
            $leafSettings->getNode()->getId()
        );

        if ($lastNotFreeItemPosition === null) {
            $lastNotFreeItemPosition = 0;
        }

        if ($leafSettings->getCapacity() < $lastNotFreeItemPosition) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}
