<?php

namespace App\Warehouse\Service\Chart\WarehouseItemStatus;

use App\Warehouse\Model\Enum\WarehouseItemStatusEnum;
use App\Warehouse\Repository\WarehouseItemHistoryRepository;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;

class ChangesChart
{
    use ChartBackgroundColorTrait;

    private ChartBuilderInterface $chartBuilder;
    private WarehouseItemHistoryRepository $warehouseItemHistoryRepository;

    public function __construct(
        ChartBuilderInterface $chartBuilder,
        WarehouseItemHistoryRepository $warehouseItemHistoryRepository
    ) {
        $this->chartBuilder = $chartBuilder;
        $this->warehouseItemHistoryRepository = $warehouseItemHistoryRepository;
    }

    public function buildChart(): Chart
    {
        $chart = $this->chartBuilder->createChart(Chart::TYPE_BAR);
        $chartData = $this->warehouseItemHistoryRepository->getDataForChangesChart();
        $dateLabels = array_values(array_unique(array_column($chartData, 'createdAtDate')));
        $datasets = $this->createDatasets($chartData, $dateLabels);

        $chart->setData([
            'labels' => $dateLabels,
            'datasets' => $datasets,
        ]);

        $chart->setOptions([
            'scales' => [
                'y' => [
                    'suggestedMin' => 0,
                    'suggestedMax' => 10,
                ],
            ],
        ]);

        return $chart;
    }

    private function createDatasets(array $chartData, array $dateLabels): array
    {
        $datasets = [];
        $datasetsNumericArray = [];
        $defaultData = [];

        $statusesNames = WarehouseItemStatusEnum::names();

        foreach ($dateLabels as $dateLabel) {
            $defaultData[$dateLabel] = 0;
        }

        foreach ($statusesNames as $statusName) {
            $datasets[$statusName] = [
                'label' => $statusName,
                'backgroundColor' => [],
                'borderColor' => [],
                'data' => $defaultData,
            ];
        }

        foreach ($chartData as $datum) {
            $dataset = $datasets[$datum['status']];
            $dataset['backgroundColor'][] = $this->getStatusBackgroundColor($datum['status']);
            $dataset['data'][$datum['createdAtDate']] = $datum['count'];

            $datasets[$datum['status']] = $dataset;
        }

        foreach ($datasets as $dataset) {
            $datasetsNumericArray[] = $dataset;
        }

        return $datasetsNumericArray;
    }
}
