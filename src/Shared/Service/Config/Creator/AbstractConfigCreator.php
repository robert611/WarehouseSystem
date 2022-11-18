<?php

namespace App\Shared\Service\Config\Creator;

use Doctrine\ORM\EntityManagerInterface;

abstract class AbstractConfigCreator
{
    protected EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function createConfigEntriesIfNotExists(): void
    {
        $class = static::ENTITY_CLASS;
        $constants = $class::DEFAULT_CONFIG_VALUES;

        foreach ($constants as $name => $value) {
            if ($this->doesConfigEntryExist($name)) {
                continue;
            }
            $configEntity = $this->createConfigEntity($name, $value);
            $this->entityManager->persist($configEntity);
        }

        $this->entityManager->flush();
    }

    public abstract function createConfigEntity(string $name, string $value);

    public abstract function doesConfigEntryExist(string $name): bool;
}