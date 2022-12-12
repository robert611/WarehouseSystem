<?php

namespace App\Warehouse\Service\WarehouseItemStatus\Chart;

use App\Warehouse\Model\Enum\WarehouseItemStatusEnum;
use App\Warehouse\Repository\WarehouseItemRepository;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;

class CountChart
{
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

    private function getStatusBackgroundColor(string $statusName): string
    {
        $backgroundColors = [
            (WarehouseItemStatusEnum::FREE)->toString() => '#388e3c',
            (WarehouseItemStatusEnum::RESERVED)->toString() => '#0277bd',
            (WarehouseItemStatusEnum::TAKEN)->toString() => '#c62828',
            (WarehouseItemStatusEnum::BLOCKED)->toString() => '#fb8c00',
        ];

        return $backgroundColors[$statusName];
    }
}
