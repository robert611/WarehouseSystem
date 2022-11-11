<?php

namespace App\DataFixtures;

use App\Warehouse\Entity\WarehouseStructureTree;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class WarehouseStructureTreeFixtures extends Fixture
{
    public const NODE_CONTAINING_ITEMS_NAME = 'CONTAIN';
    public const NODE_CONTAINING_CHILDREN_REFERENCE = 'CONTAINING_CHILDREN';

    public function load(ObjectManager $manager): void
    {
        $nodeContainingItems = new WarehouseStructureTree();
        $nodeContainingItems->setName(self::NODE_CONTAINING_ITEMS_NAME);
        $nodeContainingItems->setParent(null);
        $nodeContainingItems->setIsLeaf(true);
        $nodeContainingItems->setTreePath(self::NODE_CONTAINING_ITEMS_NAME);

        $manager->persist($nodeContainingItems);
        $manager->flush();

        $this->addReference(self::NODE_CONTAINING_CHILDREN_REFERENCE, $nodeContainingItems);
    }
}