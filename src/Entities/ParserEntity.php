<?php


namespace mdao\QueryOrmServer\Entities;

use mdao\QueryOrmServer\Contracts\Arrayable;

class ParserEntity implements Arrayable
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
     * @var array
     */
    protected $order;
    /**
     * @var QueryPagination
     */
    protected $pagination;

    public function __construct(array $filter = [], array $select = [], array $order = [], array $pagination = [])
    {
        if (!empty($filter)) {
            $this->setFilter($filter);
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
        $this->filter= QueryWheres::createFilters($filter);
        return $this;
    }

    /**
     * @return array
     */
    public function getOrder()
    {
        return $this->order;
    }


    /**
     * @param array $order
     * @return $this
     */
    public function setOrder(array $order): self
    {
        $orders = [];
        foreach ($order as $value) {
            list($field, $direction) = $value;
            $orders[] = new QueryOrderBy((string)$field, $direction);
        }

        $this->order = $orders;

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

    /**
     * @return array
     */
    public function toArray(): array
    {
        $queries = [];
        if ($this->filter) {
            $filters = $this->getFilter();
            $queries['filter'] =$filters->toArray();
        }
        if ($this->order) {
            $orders = $this->getOrder();

            $orderArray = [];
            /**
             * @var QueryOrderBy
             */
            foreach ($orders as $order) {
                $orderArray[] = $order->toArray();
            }
            $queries['order'] = $orderArray;
        }

        if ($this->pagination) {
            $queries['page'] = $this->getPagination()->toArray();
        }

        if ($this->select) {
            $queries['select'] = $this->getSelect()->toArray();
        }

        return $queries;
    }

    public function toJson()
    {
        return json_encode($this->toArray());
    }

    /**
     * @return string
     */
    public function toUriQueryString(): string
    {
        $queries = [];
        if ($this->filter) {
            $queries['filter']= $this->getFilter()->toArray();
        }

        if ($this->select) {
            $queries['select'] = implode(',', $this->getSelect()->toArray());
        }

        if ($this->order) {
            $orderBy = [];
            $sortedBy = [];
            /**
             * @var QueryOrderBy $order
             */
            foreach ($this->getOrder() as $index => $order) {
                $orderBy[] = $order->getColumn();
                $sortedBy[] = $order->getDirection();
            }

            $queries['order_by'] = implode(',', $orderBy);
            $queries['sorted_by'] = implode(',', $sortedBy);
        }
        if ($this->pagination) {
            $queries['page'] = $this->getPagination()->getPage();
            $queries['page_size'] = $this->getPagination()->getPageSize();
        }
        return http_build_query($queries);
    }
}
