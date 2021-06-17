<?php


namespace mdao\QueryOrmServer\Servers;

use mdao\QueryOrmServer\Contracts\OrmEntityContract;
use mdao\QueryOrmServer\Contracts\QueryServerContract;
use mdao\QueryOrmServer\Entities\OrmEntity;
use mdao\QueryOrmServer\Entities\ParserDataEntity;
use mdao\QueryOrmServer\Entities\QueryWhere;
use mdao\QueryOrmServer\Entities\QueryWhereOr;
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
     * @param array $data 创建一个 空的QueryServer 对象
     * @return static
     */
    public static function create(array $data = []): QueryServer
    {
        return new static(OrmEntity::createEntity($data));
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
        $queryWhere = new QueryWhere($key, $operation, $value);
        $this->ormEntity->addFilter($queryWhere);
        return $this;
    }

    /**
     * @param string $key
     * @param array $value
     * @return $this
     */
    public function whereIn(string $key, array $value): QueryServer
    {
        $queryWhere = new QueryWhere($key, 'in', $value);
        $this->ormEntity->addFilter($queryWhere);
        return $this;
    }

    /**
     * @param string $key
     * @param array $value
     * @return $this
     */
    public function whereBetween(string $key, array $value): QueryServer
    {
        $queryWhere = new QueryWhere($key, 'between', $value);
        $this->ormEntity->addFilter($queryWhere);
        return $this;
    }

    /**
     * @param string $key
     * @param array $value
     * @return $this
     */
    public function whereNoBetween(string $key, array $value): QueryServer
    {
        $queryWhere = new QueryWhere($key, 'not between', $value);
        $this->ormEntity->addFilter($queryWhere);
        return $this;
    }

    /**
     * 现在使用的是一个比较笨的方式，拿出所有的条件，然后再重新写入，以后优化
     * @param string $key
     * @return $this
     * @throws ParserException
     */
    public function removeWhere(string $key): QueryServer
    {
        if ($this->ormEntity->getFilter()) {

            $result = $this->parser->apply($this->parserDataEntity, [
                'filter' => $this->ormEntity->getFilter()
            ])->getFilter();

            $this->ormEntity->setFilter([]);

            /**
             * @var $item QueryWhere
             */
            foreach ($result as $index => $item) {
                if ($index !== $key) {
                    $this->where($item->getField(), $item->getOperator(), $item->getValue());
                }
            }
        }
        return $this;
    }

    /**
     * @param string $key
     * @param string $operation
     * @param null $value
     * @return $this
     */
    public function whereOr(string $key, string $operation, $value = null): QueryServer
    {
        $queryWhere = new QueryWhereOr($key, $operation, $value);
        $this->ormEntity->addWhereOr($queryWhere);
        return $this;
    }

    /**
     * @param string $key
     * @param array $value
     * @return $this
     */
    public function whereOrIn(string $key, array $value): QueryServer
    {
        $queryWhere = new QueryWhereOr($key, 'in', $value);
        $this->ormEntity->addWhereOr($queryWhere);
        return $this;
    }

    /**
     * @param string $key
     * @param array $value
     * @return $this
     */
    public function whereOrBetween(string $key, array $value): QueryServer
    {
        $queryWhere = new QueryWhereOr($key, 'between', $value);
        $this->ormEntity->addWhereOr($queryWhere);
        return $this;
    }

    /**
     * @param string $key
     * @param array $value
     * @return $this
     */
    public function whereOrNoBetween(string $key, array $value): QueryServer
    {
        $queryWhere = new QueryWhereOr($key, 'not between', $value);
        $this->ormEntity->addWhereOr($queryWhere);
        return $this;
    }


    /**
     * 现在使用的是一个比较笨的方式，拿出所有的条件，然后再重新写入，以后优化
     * @param string $key
     * @return $this
     * @throws ParserException
     */
    public function removeWhereOr(string $key): QueryServer
    {
        if ($this->ormEntity->getWhereOr()) {

            $result = $this->parser->apply($this->parserDataEntity, [
                'filter' => $this->ormEntity->getWhereOr()
            ])->getFilter();

            $this->ormEntity->setWhereOr([]);

            /**
             * @var $item QueryWhereOr
             */
            foreach ($result as $index => $item) {
                if ($index !== $key) {
                    $this->whereOr($item->getField(), $item->getOperator(), $item->getValue());
                }
            }
        }
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
