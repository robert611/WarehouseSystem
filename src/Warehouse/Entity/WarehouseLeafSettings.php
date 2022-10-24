<?php

namespace App\Warehouse\Entity;

use App\Warehouse\Repository\WarehouseLeafSettingsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: WarehouseLeafSettingsRepository::class)]
class WarehouseLeafSettings
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $capacity;

    #[ORM\ManyToOne(targetEntity: WarehouseDimension::class, inversedBy: 'warehouseLeafSettings')]
    private ?WarehouseDimension $dimension;

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
}
