<?php


namespace mdao\QueryOrmServer\Entities;

use mdao\QueryOrmServer\Config;
use mdao\QueryOrmServer\Contracts\Arrayable;
use mdao\QueryOrmServer\Contracts\OrmEntityContract;
use mdao\QueryOrmServer\Parser;

class OrmEntity implements Arrayable
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

    protected $parser;

    /**
     * OrmEntity constructor.
     * @param $attributes
     * @param array $config
     * @throws \mdao\QueryOrmServer\Exception\ParserException
     */
    public function __construct($attributes, $config = [])
    {

        $this->config = new Config($config);
        $this->parser = new Parser();
        $filter = $attributes[$this->config->getFilter()] ?? [];

        if (is_string($filter)) {
            $filter = json_decode($filter, true);
        }

        $filter = $this->parser->where($filter);


        $whereOr = $attributes[$this->config->getWhereOr()] ?? [];

        if (is_string($whereOr)) {
            $whereOr = json_decode($whereOr, true);
        }

        $whereOr = $this->parser->where($whereOr);

        $orderBy = $attributes[$this->config->getOrderBy()] ?? 'id';
        $sortedBy = $attributes[$this->config->getSortedBy()] ?? 'desc';

        if (is_array($orderBy)) {
            $orderBy = implode(',', $orderBy);
        }
        if (is_array($sortedBy)) {
            $sortedBy = implode(',', $sortedBy);
        }

        $order = [];
        if (!empty($orderBy)) {
            $order = $this->parser->order($orderBy, $sortedBy);
        }

        $page = $attributes[$this->config->getPage()] ?? 0;
        $pageSize = $attributes[$this->config->getPageSize()] ?? 0;
        $pagination = [];
        if ($page != 0) {
            $pagination = $this->parser->pagination($page, $pageSize);
        }

        $select = $attributes[$this->config->getSelect()] ?? '';

        if (!empty($select)) {
            $select = trim($select, ',');
        }

        $select = $this->parser->select($select);

        $this->init($filter, $select, $order, $pagination, $whereOr);
    }

    /**
     * 根据规则创建一个新的实体
     * @param $attributes
     * @param array $config
     * @return static
     * @throws \mdao\QueryOrmServer\Exception\ParserException
     */
    public static function createEntity($attributes, $config = [])
    {
        return new static($attributes, $config = []);
    }

    protected function init(
        array $filter = [],
        array $select = [],
        array $order = [],
        array $pagination = [],
        array $whereOr = []
    )
    {
        if (!empty($filter)) {
            $this->setFilter($filter);
        }

        if (!empty($whereOr)) {
            $this->setWhereOr($whereOr);
        }

        if (!empty($order)) {
            $this->setOrder($order);
        }

        if (!empty($pagination)) {
            $this->setPagination($pagination);
        }

        $this->setSelect($select);
    }


    /**
     * @return QueryWheres
     */
    public function getFilter()
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
     * @return QueryWhereOrs
     */
    public function getWhereOr()
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
     * @return QueryOrderBys
     */
    public function getOrder(): QueryOrderBys
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
     * @return QuerySelect
     */
    public function getSelect()
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

    public function addFilter(QueryWhere $queryWhere)
    {
        $field = $queryWhere->parserOperator();
        $this->filter[$field] = $queryWhere->getValue();
    }

    /**
     * 添加whereor
     * @param QueryWhereOr $queryWhereOr
     */
    public function addWhereOr(QueryWhereOr $queryWhereOr)
    {
        $field = $queryWhereOr->parserOperator();
        $this->whereOr[$field] = $queryWhereOr->getValue();
    }

    /**
     * 添加排序
     * @param QueryOrderBy $queryOrderBy
     */
    public function addOrder(QueryOrderBy $queryOrderBy)
    {
        $orderBy = $this->orderBy ?? '';
        $sortedBy = $this->sortedBy ?? '';

        $orderBys = explode(',', $orderBy);
        $sortedBys = explode(',', $sortedBy);

        $orderBys[] = $queryOrderBy->getColumn();
        $sortedBys[] = $queryOrderBy->getDirection();
        $orderBys = array_unique($orderBys);

        $this->orderBy = trim(implode(',', $orderBys), ',');
        $this->sortedBy = trim(implode(',', $sortedBys), ',');
    }

    /**
     * 添加字段
     * @param QuerySelect $querySelect
     */
    public function addSelect(QuerySelect $querySelect)
    {
        $select = $this->select ?? '';

        $selects = explode(',', $select);
        if (!empty($selects)) {
            $selects = array_merge($selects, $querySelect->getSelect());
        } else {
            $selects = $querySelect->getSelect();
        }
        $selects = array_unique($selects);
        $this->select = trim(implode(',', $selects), ',');
    }

    /**
     * @return Config
     */
    public function getConfig(): ?Config
    {
        return $this->config;
    }
}
