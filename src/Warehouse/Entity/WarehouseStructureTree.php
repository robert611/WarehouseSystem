<?php

namespace App\Warehouse\Entity;

use App\Warehouse\Repository\WarehouseStructureTreeRepository;
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
}
