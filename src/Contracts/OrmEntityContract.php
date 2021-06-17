<?php


namespace mdao\QueryOrmServer\Contracts;

use mdao\QueryOrmServer\Entities\QueryWhere;
use mdao\QueryOrmServer\Entities\QueryWhereOr;
use mdao\QueryOrmServer\Entities\QueryWheres;

interface OrmEntityContract
{
    /**
     * 根据规则创建一个新的实体
     * @param $attributes
     * @return static
     */
    public static function createEntity($attributes);

    /**
     * @return array
     */
    public function getFilter(): array;

    /**
     * @param array $filter
     */
    public function setFilter(array $filter): void;

    /**
     * @return array
     */
    public function getWhereOr(): array;

    /**
     * @param array $filter
     */
    public function setWhereOr(array $filter): void;

    /**
     * @return string
     */
    public function getOrderBy(): ?string;

    /**
     * @param string $orderBy
     */
    public function setOrderBy(string $orderBy): void;

    /**
     * @return string
     */
    public function getSortedBy(): ?string;

    /**
     * @param string $sortedBy
     */
    public function setSortedBy(string $sortedBy): void;

    /**
     * @return int
     */
    public function getPage(): ?int;

    /**
     * @param int $page
     */
    public function setPage(int $page): void;

    /**
     * @return int
     */
    public function getPageSize(): ?int;

    /**
     * @param int $pageSize
     */
    public function setPageSize(int $pageSize): void;

    /**
     * @return string
     */
    public function getSelect(): string;

    /**
     * @param mixed $select
     */
    public function setSelect($select): void;


    public function addFilter(QueryWhere $queryWhere);

    public function addWhereOr(QueryWhereOr $queryWhereOr);

    public function addOrderBy();

    public function addSortedBy();

    public function addSelect();

}
