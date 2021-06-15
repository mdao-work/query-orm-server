<?php


namespace mdao\QueryOrmServer\Servers;

use mdao\QueryOrmServer\Contracts\OrmEntityContract;
use mdao\QueryOrmServer\Contracts\QueryServerContract;
use mdao\QueryOrmServer\Entities\ParserDataEntity;
use mdao\QueryOrmServer\Entities\QueryWhere;
use mdao\QueryOrmServer\Entities\QueryWhereOrs;
use mdao\QueryOrmServer\Entities\QueryWheres;
use mdao\QueryOrmServer\Entities\QueryOrderBy;
use mdao\QueryOrmServer\Entities\QueryPagination;
use mdao\QueryOrmServer\Entities\QuerySelect;
use mdao\QueryOrmServer\Exception\ParserException;
use mdao\QueryOrmServer\Parser;

class QueryServer implements QueryServerContract
{
    protected $ormEntity;
    protected $parserDataEntity;
    protected $parser;

    public function __construct(OrmEntityContract $ormEntity)
    {
        $this->ormEntity = $ormEntity;
        $this->parserDataEntity = new ParserDataEntity();
        $this->parser = new Parser();
    }

    /**
     * @return QueryWheres
     * @throws ParserException
     */
    public function getQueryWheres(): ?QueryWheres
    {
        if ($this->ormEntity->getFilter()) {
            $result = $this->parser->apply($this->parserDataEntity, [
                'filter' => $this->ormEntity->getFilter()
            ])->getFilter();

            return empty($result) ? null : $result;
        }
        return null;
    }

    public function getQueryWhereOrs(): ?QueryWhereOrs
    {
        if ($this->ormEntity->getWhereOr()) {
            $result = $this->parser->apply($this->parserDataEntity, [
                'where_or' => $this->ormEntity->getWhereOr()
            ])->getWhereOr();
            return empty($result) ? null : $result;
        }
        return null;
    }

    /**
     * @return QueryOrderBy|null
     * @throws ParserException
     */
    public function getQueryOrderBy(): ?array
    {
        if ($this->ormEntity->getOrderBy()) {
            return $this->parser->apply($this->parserDataEntity, [
                'order_by' => $this->ormEntity->getOrderBy(),
                'sorted_by' => $this->ormEntity->getSortedBy(),
            ])
                ->getOrder();
        }
        return null;
    }

    public function getQueryPagination(): ?QueryPagination
    {

        if ($result = $this->ormEntity->getPage()) {
            return $this->parser->apply($this->parserDataEntity, [
                'page' => $result,
                'page_size' => $this->ormEntity->getPageSize(),
            ])
                ->getPagination();
        }
        return null;
    }

    /**
     * @return QuerySelect|null
     * @throws ParserException
     */
    public function getQuerySelect(): ?QuerySelect
    {
        if ($result = $this->ormEntity->getSelect()) {
            return $this->parser->apply($this->parserDataEntity, [
                'select' => $result,
            ])
                ->getSelect();
        }
        return null;
    }

    /**
     * @param string $key
     * @param string $operation
     * @param null $value
     * @return $this
     */
    public function where(string $key, string $operation, $value = null): QueryServer
    {
        $this->ormEntity->setFilter([(new QueryWhere($key, $operation, $value))->toArray()]);
        return $this;
    }

    /**
     * @param string $key
     * @param mixed ...$value
     * @return $this
     */
    public function whereIn(string $key, ...$value): QueryServer
    {
        $this->ormEntity->setFilter([(new QueryWhere($key, 'in', $value))->toArray()]);
        return $this;
    }

    /**
     * @param string $key
     * @param string $value
     * @return $this
     */
    public function whereBetween(string $key, string $value): QueryServer
    {
        $this->ormEntity->setFilter([(new QueryWhere($key, 'between', $value))->toArray()]);
        return $this;
    }

    /**
     * @param string $key
     * @param string $value
     * @return $this
     */
    public function whereNoBetween(string $key, string $value): QueryServer
    {
        $this->ormEntity->setFilter([(new QueryWhere($key, 'not between', $value))->toArray()]);
        return $this;
    }

    /**
     * @param string $key
     * @param string $direction
     * @return $this
     */
    public function orderBy(string $key, string $direction = 'desc'): QueryServer
    {
        $queryOrderBy = (new QueryOrderBy($key, $direction));
        $this->ormEntity->setOrderBy($queryOrderBy->getColumn());
        $this->ormEntity->setSortedBy($queryOrderBy->getDirection());
        return $this;
    }

    /**
     * @param array $select
     * @return $this
     */
    public function addSelect(array $select): QueryServer
    {
        $this->ormEntity->setSelect((new QuerySelect($select))->toArray());
        return $this;
    }

    /**
     * @param int $pageSize
     * @param int $page
     * @return $this
     */
    public function page(int $pageSize, int $page = 10): QueryServer
    {
        $queryPagination = (new QueryPagination($page, $pageSize));
        $this->ormEntity->setPage($queryPagination->getPage());
        $this->ormEntity->setPageSize($queryPagination->getPageSize());
        return $this;
    }


}
