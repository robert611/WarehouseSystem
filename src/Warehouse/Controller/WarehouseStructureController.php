<?php

namespace App\Warehouse\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
}