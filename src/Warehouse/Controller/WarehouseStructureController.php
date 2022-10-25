<?php

namespace App\Warehouse\Controller;

use App\Warehouse\Entity\WarehouseStructureTree;
use App\Warehouse\Form\WarehouseStructureType;
use App\Warehouse\Repository\WarehouseStructureTreeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/warehouse/structure')]
class WarehouseStructureController extends AbstractController
{
    #[Route('/', name: 'app_warehouse_structure_index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('warehouse/warehouse_structure/index.html.twig');
    }

    #[Route('/', name: 'app_warehouse_structure_index', methods: ['GET, POST'])]
    public function new(Request $request, WarehouseStructureTreeRepository $warehouseStructureTreeRepository): Response
    {
        $warehouseStructure = new WarehouseStructureTree();
        $form = $this->createForm(WarehouseStructureType::class, $warehouseStructure);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $parentId = $request->request->get('parentId');
            if ($parentId) {
                $parent = $warehouseStructureTreeRepository->find($parentId);
            }

            $warehouseStructure->setParent($parent ?? null);
            $warehouseStructure->setIsLeaf(false);
            $warehouseStructure->setTreePath(isset($parent) ? $parent->getTreePath() : '' . $warehouseStructure->getName());

            $warehouseStructureTreeRepository->add($warehouseStructure, true);

            return new JsonResponse(['error' => false]);
        }

        return $this->render('warehouse/warehouse_structure/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}