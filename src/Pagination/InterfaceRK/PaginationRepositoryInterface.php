<?php

namespace App\Pagination\InterfaceRK;

use App\Pagination\Model\Pagination;

interface PaginationRepositoryInterface
{
    public function getPaginationResults(array $formData, Pagination $pagination): array;

    public function getPaginationResultsCount(array $formData): int;
}