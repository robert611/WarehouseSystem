<?php

namespace App\Warehouse\Entity;

use App\Warehouse\Repository\WarehouseLeafSettingsRepository;
use Doctrine\ORM\Mapping as ORM;
use App\Warehouse\Validator\Leaf as WarehouseAssert;

#[ORM\Entity(repositoryClass: WarehouseLeafSettingsRepository::class)]
#[WarehouseAssert\LeafCapacityContainsItems]
class WarehouseLeafSettings
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    #[WarehouseAssert\LeafCapacityInRange]
    private ?int $capacity;

    #[ORM\ManyToOne(targetEntity: WarehouseDimension::class, inversedBy: 'warehouseLeafSettings')]
    private ?WarehouseDimension $dimension;

    #[ORM\OneToOne(inversedBy: 'warehouseLeafSettings', targetEntity: WarehouseStructureTree::class, cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private WarehouseStructureTree $node;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCapacity(): ?int
    {
        return $this->capacity;
    }

    public function setCapacity(?int $capacity): self
    {
        $this->capacity = $capacity;

        return $this;
    }

    public function getDimension(): ?WarehouseDimension
    {
        return $this->dimension;
    }

    public function setDimension(?WarehouseDimension $dimension): self
    {
        $this->dimension = $dimension;

        return $this;
    }

    public function getNode(): ?WarehouseStructureTree
    {
        if (!isset($this->node)) {
            return null;
        }

        return $this->node;
    }

    public function setNode(WarehouseStructureTree $node): self
    {
        $this->node = $node;

        return $this;
    }
}
