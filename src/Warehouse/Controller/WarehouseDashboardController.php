<?php

namespace App\Warehouse\Controller;

use App\Warehouse\Service\Chart\WarehouseItemStatusCountChart;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/warehouse')]
class WarehouseDashboardController extends AbstractController
{
    private WarehouseItemStatusCountChart $warehouseItemStatusChart;

    public function __construct(WarehouseItemStatusCountChart $warehouseItemStatusChart)
    {
        $this->warehouseItemStatusChart = $warehouseItemStatusChart;
    }

    #[Route('/', name: 'app_warehouse_index', methods: ['GET'])]
    public function index(): Response
    {
        $warehouseItemStatusChart = $this->warehouseItemStatusChart->buildChart();

        return $this->render('warehouse/dashboard/index.html.twig', [
            'warehouseItemStatusChart' => $warehouseItemStatusChart,
        ]);
    }
}