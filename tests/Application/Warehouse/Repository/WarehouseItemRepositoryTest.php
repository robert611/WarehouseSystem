<?php

namespace App\Tests\Application\Warehouse\Repository;

use App\DataFixtures\WarehouseStructureTreeFixtures;
use App\Warehouse\Entity\WarehouseItem;
use App\Warehouse\Entity\WarehouseStructureTree;
use App\Warehouse\Repository\WarehouseItemRepository;
use App\Warehouse\Repository\WarehouseStructureTreeRepository;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class WarehouseItemRepositoryTest extends KernelTestCase
{
    private readonly null|ObjectManager $entityManager;
    private readonly WarehouseStructureTreeRepository $warehouseStructureTreeRepository;
    private readonly WarehouseItemRepository $warehouseItemRepository;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        $this->warehouseItemRepository = $this->entityManager->getRepository(WarehouseItem::class);
        $this->warehouseStructureTreeRepository = $this->entityManager->getRepository(WarehouseStructureTree::class);
    }

    public function testGetNotFreeLeafItemsCount(): void
    {
        $node = $this->warehouseStructureTreeRepository->findOneBy(
            ['name' => WarehouseStructureTreeFixtures::NODE_CONTAINING_ITEMS_NAME]
        );
        $warehouseItemsCount = $this->warehouseItemRepository->getNotFreeLeafItemsCount($node->getId());

        $this->assertEquals(3, $warehouseItemsCount);
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        // doing this is recommended to avoid memory leaks
        $this->entityManager->close();
        $this->entityManager = null;
    }
}
