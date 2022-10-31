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
    private WarehouseStructureTreeRepository $warehouseStructureTreeRepository;

    public function __construct(WarehouseStructureTreeRepository $warehouseStructureTreeRepository)
    {
        $this->warehouseStructureTreeRepository = $warehouseStructureTreeRepository;
    }

    #[Route('/', name: 'app_warehouse_structure_index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('warehouse/warehouse_structure/index.html.twig', [
            'treeElements' => $this->warehouseStructureTreeRepository->findWithoutParent(),
        ]);
    }

    #[Route('/open/{id}', name: 'app_warehouse_structure_open', methods: ['GET'])]
    public function open(WarehouseStructureTree $node): Response
    {
        return $this->render('warehouse/warehouse_structure/_node.html.twig', [
            'parent' => $node,
            'treeElements' => $this->warehouseStructureTreeRepository->findBy(['parent' => $node]),
        ]);
    }

    #[Route('/open/leaf/{id}', name: 'app_warehouse_structure_open_leaf', methods: ['GET'])]
    public function openLeaf(WarehouseStructureTree $node): Response
    {
        return $this->render('');
    }

    #[Route('/new', name: 'app_warehouse_structure_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $warehouseStructure = new WarehouseStructureTree();
        $form = $this->createForm(WarehouseStructureType::class, $warehouseStructure, [
            'action' => $this->generateUrl('app_warehouse_structure_new'),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();
            $parentId = $request->request->all('warehouse_structure')['parentId'];
            if ($parentId) {
                $parent = $this->warehouseStructureTreeRepository->find($parentId);
            }

            $warehouseStructure->setName(strtoupper($formData->getName()));
            $warehouseStructure->setParent($parent ?? null);
            $warehouseStructure->setIsLeaf(false);
            $warehouseStructure->setTreePath(isset($parent) ? $parent->getTreePath() : '' . $warehouseStructure->getName());

            $this->warehouseStructureTreeRepository->add($warehouseStructure, true);

            return new JsonResponse(['error' => false]);
        }

        return $this->render('warehouse/warehouse_structure/new.html.twig', [
            'form' => $form->createView(),
            'parentId' => $request->get('parentId'),
        ]);
    }
}