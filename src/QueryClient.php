<?php


namespace mdao\QueryOrm;

use mdao\QueryOrm\Entities\QueryFilter;
use mdao\QueryOrm\Entities\ParserEntity;
use mdao\QueryOrm\Entities\QueryOrderBy;
use mdao\QueryOrm\Entities\QuerySelect;
use mdao\QueryOrm\Entities\QueryPagination;

class QueryClient
{
    protected $parserEntity;

    public function __construct()
    {
        $this->parserEntity = new ParserEntity();
    }

    /**
     * @param string $key
     * @param $operation
     * @param null $value
     * @return $this
     */
    public function where(string $key, string $operation, $value = null)
    {
        $this->parserEntity->setFilter([(new QueryFilter($key, $operation, $value))->toArray()]);
        return $this;
    }

    /**
     * @param string $key
     * @param mixed ...$value
     * @return $this
     */
    public function whereIn(string $key, ...$value): QueryClient
    {
        $this->parserEntity->setFilter([(new QueryFilter($key, 'in', $value))->toArray()]);
        return $this;
    }

    /**
     * @param string $key
     * @param array $value
     * @return $this
     */
    public function whereBetween(string $key, string $value)
    {
        $this->parserEntity->setFilter([(new QueryFilter($key, 'between', $value))->toArray()]);
        return $this;
    }

    /**
     * @param string $key
     * @param array $value
     * @return $this
     */
    public function whereNoBetween(string $key, string $value): QueryClient
    {
        $this->parserEntity->setFilter([(new QueryFilter($key, 'not between', $value))->toArray()]);
        return $this;
    }

    /**
     * @param string $key
     * @param string $direction
     * @return $this
     */
    public function orderBy(string $key, string $direction = 'desc'): QueryClient
    {
        $queryOrderBy = (new QueryOrderBy($key, $direction));
        $this->parserEntity->setOrder([[$queryOrderBy->getColumn(), $queryOrderBy->getDirection()]]);
        return $this;
    }

    /**
     * @param array $select
     * @return $this
     */
    public function select(array $select): QueryClient
    {
        $this->parserEntity->setSelect((new QuerySelect($select))->toArray());
        return $this;
    }

    /**
     * @param int $perPage
     * @param int $page
     * @return $this
     */
    public function page(int $perPage, int $page = 10): QueryClient
    {
        $queryPagination = (new QueryPagination($page, $perPage));
        $this->parserEntity->setPagination([$queryPagination->getPage(), $queryPagination->getPerPage()]);
        return $this;
    }

    /**
     * Trigger method calls to the model
     *
     * @param string $method
     * @param array $arguments
     *
     * @return mixed
     */
    public function __call($method, $arguments)
    {
        return call_user_func_array([$this->parserEntity, $method], $arguments);
    }
}
