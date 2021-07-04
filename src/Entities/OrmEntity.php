<?php


namespace mdao\QueryOrmServer\Entities;

use mdao\QueryOrmServer\Config;
use mdao\QueryOrmServer\Contracts\Arrayable;
use mdao\QueryOrmServer\Contracts\OrmEntityContract;
use mdao\QueryOrmServer\Exception\ParserException;
use mdao\QueryOrmServer\Parser;

class OrmEntity
{
    /**
     * @var QuerySelect
     */
    protected $select;

    /**
     * @var QueryWheres
     */
    protected $filter;

    /**
     * @var QueryWhereOrs
     */
    protected $whereOr;

    /**
     * @var QueryOrderBys
     */
    protected $order;

    /**
     * @var QueryPagination
     */
    protected $pagination;

    /**
     * @var Config
     */
    protected $config;

    /**
     * @var Parser
     */
    protected $parser;

    /**
     * OrmEntity constructor.
     * @param $attributes
     * @param array $config
     * @throws ParserException
     */
    public function __construct($attributes, $config = [])
    {
        $this->config = new Config($config);
        $this->parser = new Parser();

        $result = $this->parser->apply($attributes, $this->config);

        if (!empty($result['where'])) {
            $this->setFilter($result['where']);
        }

        if (!empty($result['where_or'])) {
            $this->setWhereOr($result['where_or']);
        }

        if (!empty($result['order'])) {
            $this->setOrder($result['order']);
        }

        if (!empty($result['pagination'])) {
            $this->setPagination($result['pagination']);
        }
        if (!empty($result['select'])) {
            $this->setSelect($result['select']);
        }
    }

    /**
     * 根据规则创建一个新的实体
     * @param $attributes
     * @param array $config
     * @return static
     * @throws ParserException
     */
    public static function createEntity($attributes, $config = [])
    {
        return new static($attributes, $config = []);
    }

    /**
     * @return QueryWheres|null ?QueryWheres
     */
    public function getFilter(): ?QueryWheres
    {
        return $this->filter;
    }

    /**
     * @param array $filter
     * @return $this
     */
    public function setFilter(array $filter): self
    {
        $this->filter = QueryWheres::createFilters($filter);
        return $this;
    }

    /**
     * @return QueryWhereOrs|null
     */
    public function getWhereOr(): ?QueryWhereOrs
    {
        return $this->whereOr;
    }

    /**
     * @param array $filter
     * @return $this
     */
    public function setWhereOr(array $filter): self
    {
        $this->whereOr = QueryWhereOrs::createFilters($filter);
        return $this;
    }

    /**
     * @return QueryOrderBys|null
     */
    public function getOrder(): ?QueryOrderBys
    {
        return $this->order;
    }


    /**
     * @param array $order
     * @return $this
     */
    public function setOrder(array $order): self
    {
        $this->order = QueryOrderBys::create($order);
        return $this;
    }

    /**
     * @return QueryPagination
     */
    public function getPagination()
    {
        return $this->pagination;
    }

    /**
     * @param array $pagination
     * @return $this
     */
    public function setPagination(array $pagination): self
    {
        list($page, $pageSize) = $pagination;
        $this->pagination = new QueryPagination($page, $pageSize);
        return $this;
    }

    /**
     * @return QuerySelect|null
     */
    public function getSelect(): ?QuerySelect
    {
        return $this->select;
    }

    /**
     * @param array $select
     * @return $this
     */
    public function setSelect(array $select): self
    {
        $this->select = new QuerySelect($select);
        return $this;
    }

    /**
     * @param QueryWhere $queryWhere
     * @return $this
     */
    public function addFilter(QueryWhere $queryWhere)
    {
        if (is_null($this->filter)) {
            $this->filter = QueryWheres::createFilters();
        }
        $this->filter[$queryWhere->getField()] = $queryWhere;

        return $this;
    }

    /**
     * 添加
     * @param QueryWhereOr $queryWhereOr
     * @return $this
     */
    public function addWhereOr(QueryWhereOr $queryWhereOr)
    {
        if (is_null($this->whereOr)) {
            $this->whereOr = QueryWhereOrs::createFilters();
        }
        $this->whereOr[$queryWhereOr->getField()] = $queryWhereOr;
        return $this;
    }

    /**
     * 添加排序
     * @param QueryOrderBy $queryOrderBy
     * @return $this
     */
    public function addOrder(QueryOrderBy $queryOrderBy)
    {
        if (is_null($this->order)) {
            $this->order = QueryOrderBys::create();
        }

        $this->order[$queryOrderBy->getColumn()] = $queryOrderBy;

        return $this;
    }

    /**
     * 添加字段
     * @param QuerySelect $querySelect
     * @return $this
     */
    public function addSelect(QuerySelect $querySelect)
    {
        if (empty($this->select)) {
            $this->select = $querySelect;
            return $this;
        }
        $selects = array_merge($this->select->toArray(), $querySelect->getSelect());
        $this->select = (new QuerySelect($selects));
        return $this;
    }

    /**
     * @return Config
     */
    public function getConfig(): ?Config
    {
        return $this->config;
    }

}
