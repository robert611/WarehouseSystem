<?php

namespace App\Warehouse\Service\WarehouseItemStatus\Chart;

use App\Warehouse\Repository\WarehouseItemRepository;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;

class CountChart
{
    use ChartBackgroundColorTrait;

    private ChartBuilderInterface $chartBuilder;
    private WarehouseItemRepository $warehouseItemRepository;

    public function __construct(ChartBuilderInterface $chartBuilder, WarehouseItemRepository $warehouseItemRepository)
    {
        $this->chartBuilder = $chartBuilder;
        $this->warehouseItemRepository = $warehouseItemRepository;
    }

    public function buildChart(): Chart
    {
        $chart = $this->chartBuilder->createChart(Chart::TYPE_BAR);

        $groupedStatuses = $this->warehouseItemRepository->getStatusCount();

        $labels = array_column($groupedStatuses, 'status');
        $backgroundColors = array_map(function ($element) {
            return $this->getStatusBackgroundColor($element);
        }, $labels);

        $chart->setData([
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Statusy',
                    'backgroundColor' => $backgroundColors,
                    'borderColor' => $backgroundColors,
                    'data' => array_column($groupedStatuses, 'count'),
                ],
            ],
        ]);

        $chart->setOptions([
            'scales' => [
                'y' => [
                    'suggestedMin' => 0,
                    'suggestedMax' => 100,
                ],
            ],
        ]);

        return $chart;
    }
}

