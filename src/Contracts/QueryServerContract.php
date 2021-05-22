<?php


namespace mdao\QueryOrmServer\Contracts;

use mdao\QueryOrmServer\Entities\QueryWhere;
use mdao\QueryOrmServer\Entities\QueryWheres;
use mdao\QueryOrmServer\Entities\QueryOrderBy;
use mdao\QueryOrmServer\Entities\QueryPagination;
use mdao\QueryOrmServer\Entities\QuerySelect;

interface QueryServerContract
{
    /**
     * 条件
     * @return array|null
     */
    public function getQueryWheres(): ?QueryWheres;

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
