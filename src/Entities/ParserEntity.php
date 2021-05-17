<?php


namespace mdao\QueryOrm\Entities;

use mdao\QueryOrm\Contracts\Arrayable;

class ParserEntity implements Arrayable
{

    /**
     * @var QuerySelect
     */
    protected $select;

    /**
     * @var array
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
     * @return mixed
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
        foreach ($filter as $value) {
            list($field, $operator, $value) = $value;
            $this->filter[] = new QueryFilter($field, $operator, $value);
        }
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
            $filterArray = [];
            /**
             * @var QueryFilter $filter
             */
            foreach ($filters as $filter) {
                $filterArray[] = $filter[0]->toArray();
            }
            $queries['filter'] = $filterArray;
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
            $queries['filter'] = [];
            /**
             * @var QueryFilter $queryItem
             */
            foreach ($this->getFilter() as $queryItem) {
                $field = $queryItem->parserOperator();
                $queries['filter'][$field] = $queryItem->getValue();
            }
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
            $queries['page_size'] = $this->getPagination()->getPerPage();
        }
        return http_build_query($queries);
    }
}
