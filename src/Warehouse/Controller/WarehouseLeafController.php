<?php

namespace App\Warehouse\Controller;

use App\Warehouse\Entity\WarehouseLeafSettings;
use App\Warehouse\Entity\WarehouseStructureTree;
use App\Warehouse\Form\WarehouseLeafSettingsType;
use App\Warehouse\Message\ConfigureLeafItems;
use App\Warehouse\Validator\DTO\SetNodeAsLeafDTO;
use App\Warehouse\Validator\DTO\UnsetAsLeafDTO;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

#[Route('/warehouse/leaf')]
class WarehouseLeafController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private ValidatorInterface $validator;
    private TranslatorInterface $translator;
    private MessageBusInterface $bus;

    public function __construct(
        EntityManagerInterface $entityManager,
        ValidatorInterface $validator,
        TranslatorInterface $translator,
        MessageBusInterface $bus
    ) {
        $this->entityManager = $entityManager;
        $this->validator = $validator;
        $this->translator = $translator;
        $this->bus = $bus;
    }

    #[Route('/open/{id}', name: 'app_warehouse_leaf_open', methods: ['GET'])]
    public function open(WarehouseStructureTree $node): Response
    {
        return $this->render('warehouse/warehouse_leaf/index.html.twig', [
            'node' => $node,
        ]);
    }

    #[Route('/save/configuration/{id}', name: 'app_warehouse_leaf_save_configuration', methods: ['GET', 'POST'])]
    public function saveConfiguration(WarehouseStructureTree $node, Request $request): Response
    {
        $warehouseLeafSettings = $node->getWarehouseLeafSettings() ?? new WarehouseLeafSettings();
        $form = $this->createForm(WarehouseLeafSettingsType::class, $warehouseLeafSettings, [
            'action' => $this->generateUrl('app_warehouse_leaf_save_configuration', ['id' => $node->getId()])
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $warehouseLeafSettings->setNode($node);
            $this->entityManager->persist($warehouseLeafSettings);
            $this->entityManager->flush();

            $this->bus->dispatch(new ConfigureLeafItems($node->getId(), $warehouseLeafSettings->getCapacity()));

            $this->addFlash(
                'warehouse_leaf_configuration_success',
                $this->translator->trans('saved_leaf_configuration')
            );
        }

        return $this->render('warehouse/warehouse_leaf/configuration_form.html.twig', [
           'form' => $form->createView(),
        ]);
    }

    #[Route('/items/table/{node}/{page}', name: 'app_warehouse_leaf_items_table', methods: ['GET'])]
    public function renderItemsTable(WarehouseStructureTree $node, int $page = 1): Response
    {


        return $this->render('warehouse/warehouse_leaf/leaf_items_table.html.twig', [
            'node' => $node,
        ]);
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
        $unsetAsLeafDTO = new UnsetAsLeafDTO($node);
        $errors = $this->validator->validate($unsetAsLeafDTO);

        if (count($errors) > 0) {
            return new JsonResponse(['error' => true, 'errorMessage' => $errors[0]->getMessage()]);
        }

        $node->removeWarehouseItems();
        $node->setIsLeaf(false);
        $this->entityManager->flush();

        return new JsonResponse(['error' => false]);
    }
}
