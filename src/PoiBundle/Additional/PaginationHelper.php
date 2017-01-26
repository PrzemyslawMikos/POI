<?php

namespace PoiBundle\Additional;

use Doctrine\ORM\Tools\Pagination\Paginator;

class PaginationHelper extends Paginator
{
    private $totalItems;
    private $pageCount;
    private $pageSize;
    private $currentPage;

    public function __construct($query, $pageSize, $currentPage)
    {
        parent::__construct($query);
        $this->pageSize = $pageSize;
        $this->currentPage = $currentPage;
    }

    public function makePagination(){

        $this->totalItems = count($this);
        $this->pageCount = ceil($this->totalItems / $this->pageSize);
        parent::getQuery()->setFirstResult($this->pageSize * ($this->currentPage - 1))->setMaxResults($this->pageSize);
    }

    /**
     * @return mixed
     */
    public function getTotalItems()
    {
        return $this->totalItems;
    }

    /**
     * @param mixed $totalItems
     */
    public function setTotalItems($totalItems)
    {
        $this->totalItems = $totalItems;
    }

    /**
     * @return mixed
     */
    public function getPageCount()
    {
        return $this->pageCount;
    }

    /**
     * @param mixed $pageCount
     */
    public function setPageCount($pageCount)
    {
        $this->pageCount = $pageCount;
    }

    /**
     * @return mixed
     */
    public function getPageSize()
    {
        return $this->pageSize;
    }

    /**
     * @param mixed $pageSize
     */
    public function setPageSize($pageSize)
    {
        $this->pageSize = $pageSize;
    }

    /**
     * @return mixed
     */
    public function getCurrentPage()
    {
        return $this->currentPage;
    }

    /**
     * @param mixed $currentPage
     */
    public function setCurrentPage($currentPage)
    {
        $this->currentPage = $currentPage;
    }

}