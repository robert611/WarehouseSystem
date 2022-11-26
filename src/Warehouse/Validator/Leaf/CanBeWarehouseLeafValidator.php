<?php

namespace App\Warehouse\Validator\Leaf;

use App\Warehouse\Entity\WarehouseStructureTree;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Contracts\Translation\TranslatorInterface;

class CanBeWarehouseLeafValidator extends ConstraintValidator
{
    public const CONTAINS_CHILDREN_MESSAGE = 'validator.contains_children_message';

    private readonly TranslatorInterface $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof CanBeWarehouseLeaf) {
            throw new UnexpectedTypeException($constraint, CanBeWarehouseLeaf::class);
        }

        /** @var WarehouseStructureTree $node */
        $node = $value;

        if ($node->getChildren()->count() > 0) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ message }}', $this->translator->trans(self::CONTAINS_CHILDREN_MESSAGE))
                ->addViolation();
        }
    }
}