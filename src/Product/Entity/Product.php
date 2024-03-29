<?php

namespace App\Product\Entity;

use App\Product\Model\Enum\SaleTypeEnum;
use App\Product\Repository\ProductRepository;
use App\Security\Entity\User;
use App\Warehouse\Entity\WarehouseItem;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['search_engine'])]
    private int $id;

    #[ORM\Column(type: 'string', length: 86)]
    #[Groups(['search_engine'])]
    private string $name;

    #[ORM\Column(type: 'string', length: 1024)]
    private string $description;

    #[ORM\Column(type: 'float', nullable: true)]
    #[Groups(['search_engine'])]
    private ?float $auctionPrice;

    #[ORM\Column(type: 'float', nullable: true)]
    #[Groups(['search_engine'])]
    private ?float $buyNowPrice;

    #[ORM\Column(type: 'smallint')]
    #[Groups(['search_engine'])]
    private int $saleType;

    #[ORM\Column(type: 'datetime_immutable')]
    #[Groups(['search_engine'])]
    private DateTimeImmutable $createdAt;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['search_engine'])]
    private User|UserInterface $user;

    #[ORM\OneToMany(mappedBy: 'product', targetEntity: ProductPicture::class, orphanRemoval: true)]
    #[Groups(['search_engine'])]
    private Collection $productPictures;

    #[ORM\OneToMany(mappedBy: 'product', targetEntity: ProductParameter::class, cascade: ['persist'], orphanRemoval: true)]
    private Collection $parameters;

    #[ORM\OneToMany(mappedBy: 'product', targetEntity: WarehouseItem::class)]
    private Collection $warehouseItems;

    public function __construct()
    {
        $this->productPictures = new ArrayCollection();
        $this->parameters = new ArrayCollection();
        $this->warehouseItems = new ArrayCollection();
    }

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

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getAuctionPrice(): ?float
    {
        return $this->auctionPrice;
    }

    public function setAuctionPrice(?float $auctionPrice): self
    {
        $this->auctionPrice = $auctionPrice;

        return $this;
    }

    public function getBuyNowPrice(): ?float
    {
        return $this->buyNowPrice;
    }

    public function setBuyNowPrice(?float $buyNowPrice): self
    {
        $this->buyNowPrice = $buyNowPrice;

        return $this;
    }

    public function getSaleType(): int
    {
        return $this->saleType;
    }

    public function setSaleType(int|SaleTypeEnum $saleType): self
    {
        if ($saleType instanceof SaleTypeEnum) {
            $saleType = $saleType->value;
        }

        $this->saleType = $saleType;

        return $this;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeImmutable $created_at): self
    {
        $this->createdAt = $created_at;

        return $this;
    }

    public function getUser(): User|UserInterface
    {
        return $this->user;
    }

    public function setUser(User|UserInterface $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, ProductPicture>
     */
    public function getProductPictures(): Collection
    {
        return $this->productPictures;
    }

    public function addProductPicture(ProductPicture $productPicture): self
    {
        if (!$this->productPictures->contains($productPicture)) {
            $this->productPictures[] = $productPicture;
            $productPicture->setProduct($this);
        }

        return $this;
    }

    public function removeProductPicture(ProductPicture $productPicture): self
    {
        if ($this->productPictures->removeElement($productPicture)) {
            // set the owning side to null (unless already changed)
            if ($productPicture->getProduct() === $this) {
                $productPicture->setProduct(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ProductParameter>
     */
    public function getParameters(): Collection
    {
        return $this->parameters;
    }


    public function addParameter(ProductParameter $productParameter): self
    {
        if (!$this->parameters->contains($productParameter)) {
            $this->parameters[] = $productParameter;
            $productParameter->setProduct($this);
        }

        return $this;
    }

    public function removeParameter(ProductParameter $productParameter): self
    {
        if ($this->parameters->removeElement($productParameter)) {
            // set the owning side to null (unless already changed)
            if ($productParameter->getProduct() === $this) {
                $productParameter->setProduct(null);
            }
        }

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
            $warehouseItem->setProduct($this);
        }

        return $this;
    }

    public function removeWarehouseItem(WarehouseItem $warehouseItem): self
    {
        if ($this->warehouseItems->removeElement($warehouseItem)) {
            // set the owning side to null (unless already changed)
            if ($warehouseItem->getProduct() === $this) {
                $warehouseItem->setProduct(null);
            }
        }

        return $this;
    }
}
