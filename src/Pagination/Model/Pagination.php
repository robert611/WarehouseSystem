<?php

namespace App\Pagination\Model;

class Pagination
{
    private ?int $pageNumber;
    private ?int $previousPage;
    private ?int $nextPage;
    private ?int $resultsPerPage;
    private array $resultsPerPageOptions = [25, 50, 100, 200];
    private array $results;

    public function __construct()
    {
        $this->pageNumber = null;
        $this->previousPage = null;
        $this->nextPage = null;
        $this->resultsPerPage = null;
        $this->results = [];
    }

    public function getPageNumber(): ?int
    {
        return $this->pageNumber;
    }

    public function setPageNumber(int $pageNumber): void
    {
        $this->pageNumber = $pageNumber;
    }

    public function getPreviousPage(): ?int
    {
        return $this->previousPage;
    }

    public function setPreviousPage(int $previousPage): void
    {
        $this->previousPage = $previousPage;
    }

    public function getNextPage(): ?int
    {
        return $this->nextPage;
    }

    public function setNextPage(int $nextPage): void
    {
        $this->nextPage = $nextPage;
    }

    public function getResultsPerPage(): ?int
    {
        return $this->resultsPerPage;
    }

    public function setResultsPerPage(int $resultsPerPage): void
    {
        $this->resultsPerPage = $resultsPerPage;
    }

    public function getResultsPerPageOptions(): array
    {
        return $this->resultsPerPageOptions;
    }

    public function setResultsPerPageOptions(array $resultsPerPageOptions): void
    {
        $this->resultsPerPageOptions = $resultsPerPageOptions;
    }

    public function getResults(): array
    {
        return $this->results;
    }

    public function setResults(array $results): void
    {
        $this->results = $results;
    }
}
