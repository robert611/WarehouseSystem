<?php

namespace App\Shared\Service\Config\Creator;

use Doctrine\ORM\EntityManagerInterface;
use ReflectionClass;

abstract class AbstractConfigCreator
{
    protected EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function createConfigEntriesIfNotExists(): void
    {
        $reflectionClass = new ReflectionClass(static::class);
        $constants = $reflectionClass->getConstants();

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