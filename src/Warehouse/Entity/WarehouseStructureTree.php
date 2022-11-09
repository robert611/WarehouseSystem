<?php

namespace App\Warehouse\Entity;

use App\Warehouse\Repository\WarehouseStructureTreeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: WarehouseStructureTreeRepository::class)]
class WarehouseStructureTree
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 8)]
    private string $name;

    #[ORM\Column(type: 'boolean')]
    private bool $isLeaf;

    #[ORM\ManyToOne(targetEntity: self::class)]
    private ?WarehouseStructureTree $parent;

    #[ORM\Column(type: 'string', length: 255)]
    private string $treePath;

    #[ORM\OneToOne(mappedBy: 'node', targetEntity: WarehouseLeafSettings::class, cascade: ['persist', 'remove'])]
    private ?WarehouseLeafSettings $warehouseLeafSettings;

    #[ORM\OneToMany(mappedBy: 'node', targetEntity: WarehouseItem::class, orphanRemoval: true)]
    private Collection $warehouseItems;

    public function __construct()
    {
        $this->warehouseItems = new ArrayCollection();
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

    public function isLeaf(): ?bool
    {
        return $this->isLeaf;
    }

    public function setIsLeaf(bool $isLeaf): self
    {
        $this->isLeaf = $isLeaf;

        return $this;
    }

    public function getParent(): ?self
    {
        return $this->parent;
    }

    public function setParent(?self $parent): self
    {
        $this->parent = $parent;

        return $this;
    }

    public function getTreePath(): string
    {
        return $this->treePath;
    }

    public function setTreePath(string $treePath): self
    {
        $this->treePath = $treePath;

        return $this;
    }

    public function __toString(): string
    {
        return $this->getName();
    }

    public function getWarehouseLeafSettings(): ?WarehouseLeafSettings
    {
        return $this->warehouseLeafSettings;
    }

    public function setWarehouseLeafSettings(WarehouseLeafSettings $warehouseLeafSettings): self
    {
        // set the owning side of the relation if necessary
        if ($warehouseLeafSettings->getNode() !== $this) {
            $warehouseLeafSettings->setNode($this);
        }

        $this->warehouseLeafSettings = $warehouseLeafSettings;

        return $this;
    }

    /**
     * @return Collection<int, WarehouseItem>
     */
    public function getWarehouseItems(): Collection
    {
        return $this->warehouseItems;
    }

    public function addWarehouseItem(WarehouseItem $warehouseItem): self
    {
        if (!$this->warehouseItems->contains($warehouseItem)) {
            $this->warehouseItems[] = $warehouseItem;
            $warehouseItem->setNode($this);
        }

        return $this;
    }

    public function removeWarehouseItem(WarehouseItem $warehouseItem): self
    {
        if ($this->warehouseItems->removeElement($warehouseItem)) {
            // set the owning side to null (unless already changed)
            if ($warehouseItem->getNode() === $this) {
                $warehouseItem->setNode(null);
            }
        }

        return $this;
    }
}
