<?php

namespace App\Warehouse\Controller;

use App\Warehouse\Service\Chart\WarehouseItemStatus\ChangesChart;
use App\Warehouse\Service\Chart\WarehouseItemStatus\CountChart;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/warehouse')]
class WarehouseDashboardController extends AbstractController
{
    private CountChart $countChart;
    private ChangesChart $changesChart;

    public function __construct(CountChart $countChart, ChangesChart $changesChart)
    {
        $this->countChart = $countChart;
        $this->changesChart = $changesChart;
    }

    #[Route('/', name: 'app_warehouse_index', methods: ['GET'])]
    public function index(): Response
    {
        $countChart = $this->countChart->buildChart();
        $changesChart = $this->changesChart->buildChart();

        return $this->render('warehouse/dashboard/index.html.twig', [
            'countChart' => $countChart,
            'changesChart' => $changesChart,
        ]);
    }
}