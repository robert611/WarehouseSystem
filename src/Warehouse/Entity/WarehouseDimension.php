<?php

namespace App\Warehouse\Entity;

use App\Warehouse\Repository\WarehouseDimensionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: WarehouseDimensionRepository::class)]
class WarehouseDimension
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 12)]
    private string $name;

    #[ORM\OneToMany(mappedBy: 'dimension', targetEntity: WarehouseLeafSettings::class)]
    private Collection $warehouseLeafSettings;

    public function __construct()
    {
        $this->warehouseLeafSettings = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, WarehouseLeafSettings>
     */
    public function getWarehouseLeafSettings(): Collection
    {
        return $this->warehouseLeafSettings;
    }

    public function addWarehouseLeafSetting(WarehouseLeafSettings $warehouseLeafSetting): self
    {
        if (!$this->warehouseLeafSettings->contains($warehouseLeafSetting)) {
            $this->warehouseLeafSettings[] = $warehouseLeafSetting;
            $warehouseLeafSetting->setDimension($this);
        }

        return $this;
    }

    public function removeWarehouseLeafSetting(WarehouseLeafSettings $warehouseLeafSetting): self
    {
        if ($this->warehouseLeafSettings->removeElement($warehouseLeafSetting)) {
            // set the owning side to null (unless already changed)
            if ($warehouseLeafSetting->getDimension() === $this) {
                $warehouseLeafSetting->setDimension(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->name;
    }
}
