<?php

namespace App\DataFixtures;

use App\Product\Entity\ProductPictureType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProductPictureTypeFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $galleryType = new ProductPictureType();
        $galleryType->setValue('gallery');

        $thumbnailType = new ProductPictureType();
        $thumbnailType->setValue('thumbnail');

        $manager->persist($galleryType);
        $manager->persist($thumbnailType);
        $manager->flush();
    }
}
