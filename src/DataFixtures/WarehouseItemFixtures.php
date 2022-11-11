<?php

namespace App\DataFixtures;

use App\Product\Entity\Product;
use App\Warehouse\Entity\WarehouseItem;
use App\Warehouse\Entity\WarehouseStructureTree;
use App\Warehouse\Model\Enum\WarehouseItemStatusEnum;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class WarehouseItemFixtures extends Fixture implements DependentFixtureInterface
{
    public const WAREHOUSE_ITEM_FREE = 'WAREHOUSE_ITEM_FREE';
    public const WAREHOUSE_ITEM_RESERVED = 'WAREHOUSE_ITEM_RESERVED';
    public const WAREHOUSE_ITEM_TAKEN = 'WAREHOUSE_ITEM_TAKEN';
    public const WAREHOUSE_ITEM_BLOCKED = 'WAREHOUSE_ITEM_BLOCKED';

    public function load(ObjectManager $manager): void
    {
        $warehouseItemsData = $this->getWarehouseItems();

        foreach ($warehouseItemsData as $data) {
            /** @var null|Product $product */
            $product = $data['product'] ? $this->getReference($data['product']) : null;
            /** @var WarehouseStructureTree $warehouseStructureTreeNode */
            $warehouseStructureTreeNode = $this->getReference($data['node']);

            $warehouseItem = new WarehouseItem();
            $warehouseItem->setProduct($product);
            $warehouseItem->setIdentifier($data['identifier']);
            $warehouseItem->setStatus($data['status']);
            $warehouseItem->setNode($warehouseStructureTreeNode);

            $manager->persist($warehouseItem);

            $this->addReference($data['reference'], $warehouseItem);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            WarehouseStructureTreeFixtures::class,
            ProductFixtures::class,
        ];
    }

    private function getWarehouseItems(): array
    {
        return [
            [
                'product' => null,
                'identifier' => WarehouseStructureTreeFixtures::NODE_CONTAINING_ITEMS_NAME . '001',
                'status' => (WarehouseItemStatusEnum::FREE)->toString(),
                'node' => WarehouseStructureTreeFixtures::NODE_CONTAINING_CHILDREN_REFERENCE,
                'reference' => self::WAREHOUSE_ITEM_FREE,
            ],
            [
                'product' => null,
                'identifier' => WarehouseStructureTreeFixtures::NODE_CONTAINING_ITEMS_NAME . '002',
                'status' => (WarehouseItemStatusEnum::RESERVED)->toString(),
                'node' => WarehouseStructureTreeFixtures::NODE_CONTAINING_CHILDREN_REFERENCE,
                'reference' => self::WAREHOUSE_ITEM_RESERVED,
            ],
            [
                'product' => ProductFixtures::TEST_PRODUCT_REFERENCE,
                'identifier' => WarehouseStructureTreeFixtures::NODE_CONTAINING_ITEMS_NAME . '003',
                'status' => (WarehouseItemStatusEnum::TAKEN)->toString(),
                'node' => WarehouseStructureTreeFixtures::NODE_CONTAINING_CHILDREN_REFERENCE,
                'reference' => self::WAREHOUSE_ITEM_TAKEN,
            ],
            [
                'product' => null,
                'identifier' => WarehouseStructureTreeFixtures::NODE_CONTAINING_ITEMS_NAME . '004',
                'status' => (WarehouseItemStatusEnum::BLOCKED)->toString(),
                'node' => WarehouseStructureTreeFixtures::NODE_CONTAINING_CHILDREN_REFERENCE,
                'reference' => self::WAREHOUSE_ITEM_BLOCKED,
            ],
        ];
    }
}
