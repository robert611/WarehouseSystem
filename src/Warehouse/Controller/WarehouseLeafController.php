<?php

namespace App\Warehouse\Controller;

use App\Warehouse\Entity\WarehouseStructureTree;
use App\Warehouse\Validator\DTO\SetNodeAsLeafDTO;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/warehouse/leaf')]
class WarehouseLeafController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private ValidatorInterface $validator;

    public function __construct(EntityManagerInterface $entityManager, ValidatorInterface $validator)
    {
        $this->entityManager = $entityManager;
        $this->validator = $validator;
    }

    #[Route('/open/{id}', name: 'app_warehouse_leaf_open', methods: ['GET'])]
    public function open(WarehouseStructureTree $node): Response
    {
        return $this->render('');
    }

    #[Route('/set/{id}', name: 'app_warehouse_leaf_set', methods: ['POST'])]
    public function set(WarehouseStructureTree $node): JsonResponse
    {
        $setNodeAsLeafDTO = new SetNodeAsLeafDTO($node);
        $errors = $this->validator->validate($setNodeAsLeafDTO);

        if (count($errors) > 0) {
            return new JsonResponse(['error' => true, 'errorMessage' => $errors[0]->getMessage()]);
        }

        $node->setIsLeaf(true);
        $this->entityManager->flush();

        return new JsonResponse(['error' => false]);
    }

    #[Route('/unset/{id}', name: 'app_warehouse_leaf_unset', methods: ['POST'])]
    public function unset(WarehouseStructureTree $node): JsonResponse
    {
        $node->setIsLeaf(false);
        $this->entityManager->flush();

        return new JsonResponse(['error' => false]);
    }
}
