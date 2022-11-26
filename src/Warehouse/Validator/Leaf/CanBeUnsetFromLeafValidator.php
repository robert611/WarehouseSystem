<?php

namespace App\Warehouse\Validator\Leaf;

use App\Warehouse\Entity\WarehouseStructureTree;
use App\Warehouse\Repository\WarehouseItemRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Contracts\Translation\TranslatorInterface;

class CanBeUnsetFromLeafValidator extends ConstraintValidator
{
    public const CONTAINS_ITEMS_MESSAGE = 'validator.contains_items_message';

    private readonly WarehouseItemRepository $warehouseItemRepository;
    private readonly TranslatorInterface $translator;

    public function __construct(WarehouseItemRepository $warehouseItemRepository, TranslatorInterface $translator)
    {
        $this->warehouseItemRepository = $warehouseItemRepository;
        $this->translator = $translator;
    }

    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof CanBeUnsetFromLeaf) {
            throw new UnexpectedTypeException($constraint, CanBeUnsetFromLeaf::class);
        }

        /** @var WarehouseStructureTree $node */
        $node = $value;

        if ($this->warehouseItemRepository->getNotFreeLeafItemsCount($node->getId()) > 0) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ message }}', $this->translator->trans(self::CONTAINS_ITEMS_MESSAGE))
                ->addViolation();
        }
    }
}
