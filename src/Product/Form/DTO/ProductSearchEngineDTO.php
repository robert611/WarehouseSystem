<?php

namespace App\Product\Form\DTO;

use App\Product\Model\Enum\SaleTypeEnum;
use DateTimeImmutable;

class ProductSearchEngineDTO
{
    private ?string $name = null;
    private ?SaleTypeEnum $saleType = null;
    private ?DateTimeImmutable $createdAtFrom = null;
    private ?DateTimeImmutable $createdAtTo = null;

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getSaleType(): ?SaleTypeEnum
    {
        return $this->saleType;
    }

    public function setSaleType(?SaleTypeEnum $saleType): void
    {
        $this->saleType = $saleType;
    }

    public function getCreatedAtFrom(): ?DateTimeImmutable
    {
        return $this->createdAtFrom;
    }

    public function setCreatedAtFrom(?DateTimeImmutable $createdAtFrom): void
    {
        $this->createdAtFrom = $createdAtFrom;
    }

    public function getCreatedAtTo(): ?DateTimeImmutable
    {
        return $this->createdAtTo;
    }

    public function setCreatedAtTo(?DateTimeImmutable $createdAtTo): void
    {
        $this->createdAtTo = $createdAtTo;
    }
}