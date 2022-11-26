<?php

namespace App\Shared\Service\Config\Creator;

use App\Shared\Entity\Interface\ConfigEntity;
use Doctrine\ORM\EntityManagerInterface;

abstract class AbstractConfigCreator
{
    public const ENTITY_CLASS = null;

    protected EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function createConfigEntriesIfNotExists(): void
    {
        $class = static::ENTITY_CLASS;
        $constants = $class ? $class::DEFAULT_CONFIG_VALUES : [];

        foreach ($constants as $name => $value) {
            if ($this->doesConfigEntryExist($name)) {
                continue;
            }
            $configEntity = $this->createConfigEntity($name, $value);
            $this->entityManager->persist($configEntity);
        }

        $this->entityManager->flush();
    }

    public abstract function createConfigEntity(string $name, string $value): ConfigEntity;

    public abstract function doesConfigEntryExist(string $name): bool;
}