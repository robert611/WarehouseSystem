<?php

namespace App\Pagination\Service;

trait BasePaginationTrait
{
    public function getPreviousPage(int $pageNumber, int $pagesCount): int
    {
        if ($pageNumber < 1 || $pageNumber >= $pagesCount) {
            return 1;
        }

        return $pageNumber;
    }

    public function getNextPage(int $pageNumber, int $pagesCount): int
    {
        if ($pageNumber > $pagesCount) {
            return $pagesCount;
        }

        return $pageNumber;
    }

    public function getPagesCount(int $totalItems, int $resultsPerPage): int
    {
        return ceil($totalItems / $resultsPerPage);
    }

    private function getValidatedPageNumber(int $pageNumber, int $pagesCount): int
    {
        if ($pageNumber < 1) {
            return 1;
        }

        if ($pageNumber > $pagesCount) {
            return $pagesCount;
        }

        return $pageNumber;
    }
}
