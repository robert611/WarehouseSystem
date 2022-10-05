<?php

namespace App\Product\Entity;

use App\Product\Model\Enum\SaleTypeEnum;
use App\Product\Repository\ProductRepository;
use App\Security\Entity\User;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 86)]
    private string $name;

    #[ORM\Column(type: 'string', length: 1024)]
    private string $description;

    #[ORM\Column(type: 'float', nullable: true)]
    private ?float $auctionPrice;

    #[ORM\Column(type: 'float', nullable: true)]
    private ?float $buyNowPrice;

    #[ORM\Column(type: 'smallint')]
    private int $saleType;

    #[ORM\Column(type: 'datetime_immutable')]
    private DateTimeImmutable $createdAt;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private User $user;

    #[ORM\OneToMany(mappedBy: 'product', targetEntity: ProductPicture::class, orphanRemoval: true)]
    private Collection $productPictures;

    #[ORM\OneToMany(mappedBy: 'product', targetEntity: ProductParameter::class, orphanRemoval: true)]
    private Collection $productParameters;

    public function __construct()
    {
        $this->productPictures = new ArrayCollection();
        $this->productParameters = new ArrayCollection();
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

    public function getUser(): User
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
    public function getProductParameters(): Collection
    {
        return $this->productParameters;
    }

    public function addProductParameter(ProductParameter $productParameter): self
    {
        if (!$this->productParameters->contains($productParameter)) {
            $this->productParameters[] = $productParameter;
            $productParameter->setProduct($this);
        }

        return $this;
    }

    public function removeProductParameter(ProductParameter $productParameter): self
    {
        if ($this->productParameters->removeElement($productParameter)) {
            // set the owning side to null (unless already changed)
            if ($productParameter->getProduct() === $this) {
                $productParameter->setProduct(null);
            }
        }

        return $this;
    }
}
