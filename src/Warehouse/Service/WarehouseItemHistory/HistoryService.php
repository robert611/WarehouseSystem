<?php

namespace App\Warehouse\Service\WarehouseItemHistory;

use App\Security\Entity\User;
use App\Warehouse\Entity\WarehouseItem;
use App\Warehouse\Model\Enum\WarehouseItemStatusEnum;
use App\Warehouse\Service\Factory\WarehouseItemHistoryFactory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class HistoryService
{
    private readonly EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function createItemHistory(
        WarehouseItem $warehouseItem,
        UserInterface|User $user,
        null|WarehouseItemStatusEnum $customStatus = null
    ): void {
        $warehouseItemHistory = WarehouseItemHistoryFactory::createItemHistory(
            $warehouseItem,
            $user,
            $customStatus ?? null
        );

        $this->entityManager->persist($warehouseItemHistory);
    }

    public function createAndSaveItemHistory(
        WarehouseItem $warehouseItem,
        UserInterface|User $user,
        null|WarehouseItemStatusEnum $customStatus = null
    ): void {
        $this->createItemHistory($warehouseItem, $user, $customStatus);

        $this->entityManager->flush();
    }
}
