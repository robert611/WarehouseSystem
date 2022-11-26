<?php

namespace App\Warehouse\MessageHandler;

use App\Warehouse\Entity\WarehouseStructureTree;
use App\Warehouse\Message\ConfigureLeafItems;
use App\Warehouse\Repository\WarehouseStructureTreeRepository;
use App\Warehouse\Service\Factory\WarehouseItemFactory;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class ConfigureLeafItemsHandler
{
    private EntityManagerInterface $entityManager;
    private WarehouseStructureTreeRepository $warehouseStructureTreeRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        WarehouseStructureTreeRepository $warehouseStructureTreeRepository
    ) {
        $this->entityManager = $entityManager;
        $this->warehouseStructureTreeRepository = $warehouseStructureTreeRepository;
    }

    public function __invoke(ConfigureLeafItems $message): void
    {
        $node = $this->warehouseStructureTreeRepository->find($message->getNodeId());
        $warehouseItems = $node->getWarehouseItems();
        $currentCapacity = $warehouseItems->count();

        $capacityIncrease = $message->getCapacity() - $currentCapacity;

        if ($capacityIncrease === 0) {
            return;
        } elseif ($capacityIncrease > 0) {
            $this->createItems($node, $capacityIncrease, $currentCapacity);
        } else {
            $this->reduceItems($node, $warehouseItems, $capacityIncrease);
        }
    }

    private function createItems(WarehouseStructureTree $node, int $quantity, int $lastTakenPosition): void
    {
        for ($i = 1; $i <= $quantity; $i++) {
            $position = $lastTakenPosition + $i;
            $warehouseItem = WarehouseItemFactory::createItem($node, $position);
            $this->entityManager->persist($warehouseItem);
        }

        $this->entityManager->flush();
    }

    private function reduceItems(WarehouseStructureTree $node, Collection $warehouseItems, int $quantity): void
    {
        $quantity = abs($quantity);

        for ($i = $quantity; $i >= 1; $i--) {
            $node->removeWarehouseItem($warehouseItems->last());
        }

        $this->entityManager->flush();
    }
}