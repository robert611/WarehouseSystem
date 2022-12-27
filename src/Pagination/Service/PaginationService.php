<?php

namespace App\Pagination\Service;

use App\Pagination\InterfaceRK\PaginationRepositoryInterface;
use App\Pagination\Model\Pagination;

class PaginationService
{
    use BasePaginationTrait;

    public const DEFAULT_RESULTS_PER_PAGE = 50;

    public function paginate(
        array $formData,
        Pagination $pagination,
        PaginationRepositoryInterface $repository
    ): Pagination{
        $totalItems = $repository->getPaginationResultsCount($formData);
        $pagesCount = $this->getPagesCount($totalItems, $pagination->getResultsPerPage());
        $pagination->setPagesCount($pagesCount);
        $pagination->setPageNumber($this->getValidatedPageNumber($pagination->getPageNumber(), $pagesCount));
        $pagination->setPreviousPage($this->getPreviousPage($pagination->getPageNumber(), $pagesCount));
        $pagination->setNextPage($this->getNextPage($pagination->getPageNumber(), $pagesCount));

        if ($pagesCount > 1) {
            $results = $repository->getPaginationResults($formData, $pagination);
            $pagination->setResults($results);
        } else {
            $pagination->setResults([]);
        }

        return $pagination;
    }

    public static function createFromScratch(int $pageNumber, ?int $defaultResultsPerPage = null): Pagination
    {
        if (is_null($defaultResultsPerPage)) {
            $defaultResultsPerPage = self::DEFAULT_RESULTS_PER_PAGE;
        }

        $pagination = new Pagination();
        $pagination->setPageNumber($pageNumber);
        $pagination->setResultsPerPage($defaultResultsPerPage);

        return $pagination;
    }
}
