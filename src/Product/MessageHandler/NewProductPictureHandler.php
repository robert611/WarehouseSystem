<?php

namespace App\Product\MessageHandler;

use App\Product\Entity\ProductPicture;
use App\Product\Message\NewProductPicture;
use App\Product\Repository\ProductPictureRepository;
use App\Product\Repository\ProductPictureTypeRepository;
use App\Product\Repository\ProductRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class NewProductPictureHandler
{
    private ProductPictureRepository $productPictureRepository;
    private ProductRepository $productRepository;
    private ProductPictureTypeRepository $productPictureTypeRepository;

    public function __construct(
        ProductPictureRepository $productPictureRepository,
        ProductRepository $productRepository,
        ProductPictureTypeRepository $productPictureTypeRepository
    ) {
        $this->productPictureRepository = $productPictureRepository;
        $this->productRepository = $productRepository;
        $this->productPictureTypeRepository = $productPictureTypeRepository;
    }

    public function __invoke(NewProductPicture $message): void
    {
        $productPicture = new ProductPicture();

        $productPicture->setPath($message->getPath());
        $productPicture->setProduct($this->productRepository->find($message->getProductId()));
        $productPicture->setType($this->productPictureTypeRepository->find($message->getTypeId()));
        $this->productPictureRepository->add($productPicture, true);
    }
}