<?php

namespace mdao\QueryOrm\Entities;

use mdao\QueryOrm\Contracts\Arrayable;

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
    protected $perPage = 15;

    /**
     * QueryPagination constructor.
     * @param int $page
     * @param int $perPage
     */
    public function __construct(int $page = 1, int $perPage = 15)
    {
        $this->setPage($page);
        $this->setPerPage($perPage);
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
    public function getPerPage(): int
    {
        return $this->perPage;
    }

    /**
     * @param int $perPage
     */
    public function setPerPage(int $perPage): void
    {
        $this->perPage = $perPage;
    }

    /**
     * @return array
     */
    public function toArray():array
    {
        return [
            'page' => $this->getPage(),
            'page_size' => $this->getPerPage(),
        ];
    }
}
