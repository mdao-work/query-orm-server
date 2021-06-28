<?php


namespace mdao\QueryOrmServer\Entities;

class OrmParamEntity
{
    protected $filter;
    protected $whereOr;
    protected $orderBy;
    protected $sortedBy;
    protected $page;
    protected $pageSize;
    protected $select;

    /**
     * OrmParamEntity constructor.
     * @param string $filter
     * @param string $whereOr
     * @param string $orderBy
     * @param string $sortedBy
     * @param string $page
     * @param string $pageSize
     * @param string $select
     */
    public function __construct(
        string $filter = 'filter',
        string $whereOr = 'where_or',
        string $orderBy = 'order_by',
        string $sortedBy = 'sorted_by',
        string $page = 'page',
        string $pageSize = 'page_size',
        string $select = 'select'
    )
    {
        $this->filter = $filter;
        $this->whereOr = $whereOr;
        $this->orderBy = $orderBy;
        $this->sortedBy = $sortedBy;
        $this->page = $page;
        $this->pageSize = $pageSize;
        $this->select = $select;
    }

    /**
     * @return string
     */
    public function getFilter(): string
    {
        return $this->filter;
    }

    /**
     * @param string $filter
     */
    public function setFilter(string $filter): void
    {
        $this->filter = $filter;
    }

    /**
     * @return string
     */
    public function getWhereOr(): string
    {
        return $this->whereOr;
    }

    /**
     * @param string $whereOr
     */
    public function setWhereOr(string $whereOr): void
    {
        $this->whereOr = $whereOr;
    }

    /**
     * @return string
     */
    public function getOrderBy(): string
    {
        return $this->orderBy;
    }

    /**
     * @param string $orderBy
     */
    public function setOrderBy(string $orderBy): void
    {
        $this->orderBy = $orderBy;
    }

    /**
     * @return string
     */
    public function getSortedBy(): string
    {
        return $this->sortedBy;
    }

    /**
     * @param string $sortedBy
     */
    public function setSortedBy(string $sortedBy): void
    {
        $this->sortedBy = $sortedBy;
    }

    /**
     * @return string
     */
    public function getPage(): string
    {
        return $this->page;
    }

    /**
     * @param string $page
     */
    public function setPage(string $page): void
    {
        $this->page = $page;
    }

    /**
     * @return string
     */
    public function getPageSize(): string
    {
        return $this->pageSize;
    }

    /**
     * @param string $pageSize
     */
    public function setPageSize(string $pageSize): void
    {
        $this->pageSize = $pageSize;
    }

    /**
     * @return string
     */
    public function getSelect(): string
    {
        return $this->select;
    }

    /**
     * @param string $select
     */
    public function setSelect(string $select): void
    {
        $this->select = $select;
    }
}
