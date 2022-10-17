<?php

namespace App\Product\Message;

class NewProductPicture
{
    private string $path;
    private int $productId;
    private int $typeId;

    public function __construct(string $path, int $productId, int $typeId)
    {
        $this->path = $path;
        $this->productId = $productId;
        $this->typeId = $typeId;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getProductId(): int
    {
        return $this->productId;
    }

    public function getTypeId(): int
    {
        return $this->typeId;
    }
}