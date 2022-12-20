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
}
