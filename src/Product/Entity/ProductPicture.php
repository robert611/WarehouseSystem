<?php

namespace App\Product\Entity;

use App\Product\Repository\ProductPictureRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ProductPictureRepository::class)]
class ProductPicture
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['search_engine'])]
    private string $path;

    #[ORM\ManyToOne(targetEntity: Product::class, inversedBy: 'productPictures')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Product $product;

    #[ORM\ManyToOne(targetEntity: ProductPictureType::class)]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['search_engine'])]
    private ProductPictureType $type;

    public function getId(): int
    {
        return $this->id;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function setPath(string $path): self
    {
        $this->path = $path;

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

    public function getType(): ProductPictureType
    {
        return $this->type;
    }

    public function setType(ProductPictureType $type): self
    {
        $this->type = $type;

        return $this;
    }
}
