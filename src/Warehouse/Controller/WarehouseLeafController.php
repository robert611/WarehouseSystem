<?php

namespace App\Warehouse\Controller;

use App\Warehouse\Entity\WarehouseStructureTree;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/warehouse/leaf')]
class WarehouseLeafController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/open/{id}', name: 'app_warehouse_leaf_open', methods: ['GET'])]
    public function open(WarehouseStructureTree $node): Response
    {
        return $this->render('');
    }

    #[Route('/set/{id}', name: 'app_warehouse_leaf_set', methods: ['POST'])]
    public function set(WarehouseStructureTree $node): JsonResponse
    {
        $node->setIsLeaf(true);
        $this->entityManager->flush();

        return new JsonResponse(['error' => false]);
    }

    #[Route('/unset/{id}', name: 'app_warehouse_leaf_unset', methods: ['POST'])]
    public function unset(WarehouseStructureTree $node)
    {
        $node->setIsLeaf(false);
        $this->entityManager->flush();
    }
}
