<?php

namespace App\Shared\Service\Config\Creator;

use App\Shared\Entity\ConfigInteger;
use App\Shared\Repository\ConfigIntegerRepository;
use Doctrine\ORM\EntityManagerInterface;

class ConfigIntegerCreator extends AbstractConfigCreator
{
    public const ENTITY_CLASS = ConfigInteger::class;
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
        $configEntity->setValue((int) $value);

        return $configEntity;
    }

    public function doesConfigEntryExist(string $name): bool
    {
        $entry = $this->configIntegerRepository->findOneBy(['name' => $name]);

        return (bool) $entry;
    }
}
