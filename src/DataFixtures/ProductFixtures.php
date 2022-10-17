<?php

namespace App\DataFixtures;

use App\Product\Entity\Product;
use App\Product\Model\Enum\SaleTypeEnum;
use App\Security\Entity\User;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class ProductFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        /** @var User $casualUserReference */
        $casualUserReference = $this->getReference(UserFixtures::CASUAL_USER_REFERENCE);
        $product = new Product();
        $product->setName('Test product name');
        $product->setDescription('Description of test product');
        $product->setAuctionPrice(120.50);
        $product->setBuyNowPrice(250);
        $product->setSaleType(SaleTypeEnum::BOTH);
        $product->setCreatedAt(new DateTimeImmutable());
        $product->setUser($casualUserReference);

        $manager->persist($product);
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
        ];
    }
}
