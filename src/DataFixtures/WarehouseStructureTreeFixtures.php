<?php

namespace App\DataFixtures;

use App\Warehouse\Service\Factory\WarehouseStructureTreeFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class WarehouseStructureTreeFixtures extends Fixture
{
    public const NODE_CONTAINING_ITEMS_NAME = 'CONTAIN';
    public const NODE_CONTAINING_CHILDREN_NAME = 'CHILDREN';
    public const EMPTY_NODE = 'EMPTY';
    public const NODE_CONTAINING_FREE_ITEMS_NAME = 'CONTAIN2';
    public const NODE_CONTAINING_ITEMS_REFERENCE = 'CONTAINING_ITEMS';
    public const NODE_CONTAINING_CHILDREN_REFERENCE = 'CONTAINING_CHILDREN';
    public const EMPTY_NODE_REFERENCE = 'EMPTY_NODE';
    public const NODE_CONTAINING_FREE_ITEMS_REFERENCE = 'CONTAINING_FREE_ITEMS';

    public function load(ObjectManager $manager): void
    {
        $nodeContainingChildren = WarehouseStructureTreeFactory::createNode(
            self::NODE_CONTAINING_CHILDREN_NAME,
            false,
            null
        );

        $nodeContainingItems = WarehouseStructureTreeFactory::createNode(
            self::NODE_CONTAINING_ITEMS_NAME,
            true,
            $nodeContainingChildren
        );

        $nodeContainingFreeItems = WarehouseStructureTreeFactory::createNode(
            self::NODE_CONTAINING_FREE_ITEMS_NAME,
            true,
            $nodeContainingChildren
        );

        $emptyNode = WarehouseStructureTreeFactory::createNode(
            self::EMPTY_NODE,
            false,
            $nodeContainingChildren
        );

        $manager->persist($nodeContainingChildren);
        $manager->persist($nodeContainingItems);
        $manager->persist($nodeContainingFreeItems);
        $manager->persist($emptyNode);
        $manager->flush();

        $this->addReference(self::NODE_CONTAINING_CHILDREN_REFERENCE, $nodeContainingChildren);
        $this->addReference(self::NODE_CONTAINING_ITEMS_REFERENCE, $nodeContainingItems);
        $this->addReference(self::NODE_CONTAINING_FREE_ITEMS_REFERENCE, $nodeContainingFreeItems);
        $this->addReference(self::EMPTY_NODE_REFERENCE, $emptyNode);
    }
}