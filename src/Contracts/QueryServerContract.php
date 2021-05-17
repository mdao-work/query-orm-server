<?php


namespace mdao\QueryOrm\Contracts;

use mdao\QueryOrm\Entities\QueryFilter;
use mdao\QueryOrm\Entities\QueryOrderBy;
use mdao\QueryOrm\Entities\QueryPagination;
use mdao\QueryOrm\Entities\QuerySelect;

interface QueryServerContract
{
    /**
     * 条件
     * @return array|null
     */
    public function getQueryFilter(): ?array;

    /**
     * 排序
     * @return array|null
     */
    public function getQueryOrderBy(): ?array;

    /**
     * 分页
     * @return QueryPagination|null
     */
    public function getQueryPagination(): ?QueryPagination;

    /**
     * 字段
     * @return QuerySelect|null
     */
    public function getQuerySelect(): ?QuerySelect;
}
