<?php

namespace App\Warehouse\Entity;

use App\Product\Entity\Product;
use App\Warehouse\Repository\WarehouseItemRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: WarehouseItemRepository::class)]
class WarehouseItem
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 255)]
    private string $identifier;

    #[ORM\Column(type: 'string', length: 64)]
    private string $status;

    #[ORM\ManyToOne(targetEntity: Product::class, inversedBy: 'warehouseItems')]
    private null|Product $product;

    #[ORM\ManyToOne(targetEntity: WarehouseStructureTree::class, inversedBy: 'warehouseItems')]
    #[ORM\JoinColumn(nullable: false)]
    private ?WarehouseStructureTree $node;

    #[ORM\Column(type: 'smallint')]
    private ?int $position;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdentifier(): ?string
    {
        return $this->identifier;
    }

    public function setIdentifier(string $identifier): self
    {
        $this->identifier = $identifier;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;

        return $this;
    }

    public function getNode(): ?WarehouseStructureTree
    {
        return $this->node;
    }

    public function setNode(?WarehouseStructureTree $node): self
    {
        $this->node = $node;

        return $this;
    }

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(int $position): self
    {
        $this->position = $position;

        return $this;
    }
}
