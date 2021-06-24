<?php


namespace mdao\QueryOrmServer\Servers;

use mdao\QueryOrmServer\Contracts\Arrayable;
use mdao\QueryOrmServer\Contracts\OrmEntityContract;
use mdao\QueryOrmServer\Contracts\QueryServerContract;
use mdao\QueryOrmServer\Entities\OrmEntity;
use mdao\QueryOrmServer\Entities\ParserDataEntity;
use mdao\QueryOrmServer\Entities\QueryOrderBys;
use mdao\QueryOrmServer\Entities\QueryWhere;
use mdao\QueryOrmServer\Entities\QueryWhereOr;
use mdao\QueryOrmServer\Entities\QueryWhereOrs;
use mdao\QueryOrmServer\Entities\QueryWheres;
use mdao\QueryOrmServer\Entities\QueryOrderBy;
use mdao\QueryOrmServer\Entities\QueryPagination;
use mdao\QueryOrmServer\Entities\QuerySelect;
use mdao\QueryOrmServer\Entities\QueryWhereTime;
use mdao\QueryOrmServer\Exception\ParserException;
use mdao\QueryOrmServer\Parser;

class QueryServer implements QueryServerContract, Arrayable
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
     * @return QueryOrderBys|null
     * @throws ParserException
     */
    public function getQueryOrderBy(): ?QueryOrderBys
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
     * @param string|array $key
     * @param ?string $operation
     * @param null $value
     * @return $this
     */
    public function where($key, ?string $operation = null, $value = null): QueryServer
    {
        //批量数组模式
        if (is_array($key)) {
            foreach ($key as $val) {
                list($filed, $operation, $value) = $val;
                $queryWhere = new QueryWhere($filed, $operation, $value);
                $this->ormEntity->addFilter($queryWhere);
            }
        } else {
            $queryWhere = new QueryWhere($key, $operation, $value);
            $this->ormEntity->addFilter($queryWhere);
        }
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
     * 查询日期或者时间范围
     * @param string $field 字段
     * @param string|int $startTime
     * @param string|null $endTime
     * @return $this
     */
    public function whereBetweenTime(string $field, $startTime, ?string $endTime = null): QueryServer
    {
        if (is_null($endTime)) {
            $time = is_string($startTime) ? strtotime($startTime) : $startTime;
            $endTime = strtotime('+1 day', $time);
        }
        $queryWhere = new QueryWhereTime($field, 'between', [$startTime, $endTime]);
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
        $this->ormEntity->addOrder($queryOrderBy);
        return $this;
    }

    /**
     * @param array $select
     * @return $this
     */
    public function addSelect(array $select): QueryServer
    {
        $querySelect = (new QuerySelect($select));
        $this->ormEntity->addSelect($querySelect);
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

    /**
     * @return array
     * @throws ParserException
     */
    public function toArray(): array
    {
        $result = [];
        //条件
        if ($filterResult = $this->getQueryWheres()) {
            $result['filter'] = $filterResult->toArray();
        }

        //条件
        if ($whereOrResult = $this->getQueryWhereOrs()) {
            $result['where_or'] = $whereOrResult->toArray();
        }

        //排序
        if ($queryOrderBy = $this->getQueryOrderBy()) {
            $result['order'] = $queryOrderBy->toArray();
        }

        //查询指定字段
        if ($selectResult = $this->getQuerySelect()) {
            $result['select'] = $selectResult->getSelect();
        }

        if ($pagination = $this->getQueryPagination()) {
            $result['page'] = $pagination->getPage();
            $result['page_size'] = $pagination->getPageSize();
        }

        if (empty($result)) {
            return [];
        }

        return $result;
    }
}
