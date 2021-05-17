<?php


namespace mdao\QueryOrm\Contracts;

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
     * @return string
     */
    public function getOrderBy(): ?string;

    /**
     * @param int $orderBy
     */
    public function setOrderBy(int $orderBy): void;

    /**
     * @return string
     */
    public function getSortedBy(): ?string;

    /**
     * @param int $sortedBy
     */
    public function setSortedBy(int $sortedBy): void;

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
}
