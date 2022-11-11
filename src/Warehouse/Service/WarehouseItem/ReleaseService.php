<?php

namespace App\Warehouse\Service\WarehouseItem;

use App\Product\Entity\Product;
use App\Security\Entity\User;
use App\Warehouse\Model\Enum\WarehouseItemStatusEnum;
use App\Warehouse\Service\WarehouseItemHistory\HistoryService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class ReleaseService
{
    private readonly EntityManagerInterface $entityManager;
    private readonly HistoryService $historyService;

    public function __construct(EntityManagerInterface $entityManager, HistoryService $historyService)
    {
        $this->entityManager = $entityManager;
        $this->historyService = $historyService;
    }

    public function releaseProductWarehouseItems(Product $product, UserInterface|User $user): void
    {
        $warehouseItems = $product->getWarehouseItems();

        foreach ($warehouseItems as $warehouseItem) {
            $warehouseItem->setProduct(null);
            $warehouseItem->setStatus((WarehouseItemStatusEnum::FREE)->toString());

            $this->historyService->createItemHistory($warehouseItem, $user);
        }

        $this->entityManager->flush();
    }
}
