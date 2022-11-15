<?php

namespace App\Shared\Service\Config\Creator;

use App\Shared\Entity\ConfigInteger;
use App\Shared\Repository\ConfigIntegerRepository;
use Doctrine\ORM\EntityManagerInterface;

class ConfigIntegerCreator extends AbstractConfigCreator
{
    public const WAREHOUSE_LEAF_MINIMAL_CAPACITY = 0;
    public const WAREHOUSE_LEAF_MAXIMUM_CAPACITY = 200;
    public const WAREHOUSE_NODE_MAXIMUM_DEPTH = 5;

    private ConfigIntegerRepository $configIntegerRepository;

    public function __construct(EntityManagerInterface $entityManager, ConfigIntegerRepository $configIntegerRepository)
    {
        parent::__construct($entityManager);
        $this->configIntegerRepository = $configIntegerRepository;
    }

    public function createConfigEntity(string $name, string $value): ConfigInteger
    {
        $configEntity = new ConfigInteger();
        $configEntity->setName($name);
        $configEntity->setValue($value);

        return $configEntity;
    }

    public function doesConfigEntryExist(string $name): bool
    {
        $entry = $this->configIntegerRepository->findOneBy(['name' => $name]);

        return (bool) $entry;
    }
}
