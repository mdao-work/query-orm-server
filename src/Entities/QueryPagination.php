<?php

namespace mdao\QueryOrmServer\Entities;

use mdao\QueryOrmServer\Contracts\Arrayable;

/**
 * 指定分页
 */
class QueryPagination implements Arrayable
{
    /**
     * 页数
     * @var int
     */
    protected $page = 1;
    /**
     * 每页数量
     * @var int
     */
    protected $pageSize = 15;

    /**
     * QueryPagination constructor.
     * @param int $page
     * @param int $pageSize
     */
    public function __construct(int $page = 1, int $pageSize = 15)
    {
        $this->setPage($page);
        $this->setPageSize($pageSize);
    }


    /**
     * @return int
     */
    public function getPage(): int
    {
        return $this->page;
    }

    /**
     * @param int $page
     */
    public function setPage(int $page): void
    {
        $this->page = $page;
    }

    /**
     * @return int
     */
    public function getPageSize(): int
    {
        return $this->pageSize;
    }

    /**
     * @param int $PageSize
     */
    public function setPageSize(int $pageSize): void
    {
        $this->pageSize = $pageSize;
    }

    /**
     * @return array
     */
    public function toArray():array
    {
        return [
            'page' => $this->getPage(),
            'page_size' => $this->getPageSize(),
        ];
    }
}
