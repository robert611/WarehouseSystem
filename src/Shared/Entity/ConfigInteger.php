<?php

namespace App\Shared\Entity;

use App\Shared\Repository\ConfigIntegerRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ConfigIntegerRepository::class)]
class ConfigInteger
{
    public const WAREHOUSE_LEAF_MINIMAL_CAPACITY = 'WAREHOUSE_LEAF_MINIMAL_CAPACITY';
    public const WAREHOUSE_LEAF_MAXIMUM_CAPACITY = 'WAREHOUSE_LEAF_MAXIMUM_CAPACITY';
    public const WAREHOUSE_NODE_MAXIMUM_DEPTH = 'WAREHOUSE_NODE_MAXIMUM_DEPTH';

    public const DEFAULT_CONFIG_VALUES = [
        self::WAREHOUSE_LEAF_MINIMAL_CAPACITY => 0,
        self::WAREHOUSE_LEAF_MAXIMUM_CAPACITY => 200,
        self::WAREHOUSE_NODE_MAXIMUM_DEPTH => 5,
    ];

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 255)]
    private string $name;

    #[ORM\Column(type: 'integer')]
    private int $value;

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getValue(): int
    {
        return $this->value;
    }

    public function setValue(int $value): self
    {
        $this->value = $value;

        return $this;
    }
}
