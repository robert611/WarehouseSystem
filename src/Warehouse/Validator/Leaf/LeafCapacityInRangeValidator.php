<?php

namespace App\Warehouse\Validator\Leaf;

use App\Shared\Entity\ConfigInteger;
use App\Shared\Service\Config\ConfigService;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class LeafCapacityInRangeValidator extends ConstraintValidator
{
    private ConfigService $configService;

    public function __construct(ConfigService $configService)
    {
        $this->configService = $configService;
    }

    public function validate(mixed $value, Constraint $constraint)
    {
        if (!$constraint instanceof LeafCapacityInRange) {
            throw new UnexpectedTypeException($constraint, LeafCapacityInRange::class);
        }

        $minimalCapacity = $this->configService->getConfigValue(ConfigInteger::WAREHOUSE_LEAF_MINIMAL_CAPACITY);
        $maximalCapacity = $this->configService->getConfigValue(ConfigInteger::WAREHOUSE_LEAF_MAXIMUM_CAPACITY);

        if ($value < $minimalCapacity || $value > $maximalCapacity) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ minimal }}', $minimalCapacity)
                ->setParameter('{{ maximal }}', $maximalCapacity)
                ->addViolation();
        }
    }
}
