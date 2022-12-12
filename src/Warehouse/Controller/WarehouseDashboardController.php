<?php

namespace App\Warehouse\Controller;

use App\Warehouse\Service\WarehouseItemStatus\Chart\CountChart;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/warehouse')]
class WarehouseDashboardController extends AbstractController
{
    private CountChart $countChart;

    public function __construct(CountChart $countChart)
    {
        $this->countChart = $countChart;
    }

    #[Route('/', name: 'app_warehouse_index', methods: ['GET'])]
    public function index(): Response
    {
        $countChart = $this->countChart->buildChart();

        return $this->render('warehouse/dashboard/index.html.twig', [
            'countChart' => $countChart,
        ]);
    }
}