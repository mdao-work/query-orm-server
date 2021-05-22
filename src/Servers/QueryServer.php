<?php


namespace mdao\QueryOrmServer\Servers;

use mdao\QueryOrmServer\Contracts\OrmEntityContract;
use mdao\QueryOrmServer\Contracts\QueryServerContract;
use mdao\QueryOrmServer\Entities\ParserDataEntity;
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
        if ($result = $this->ormEntity->getFilter()) {
            $result = $this->parser->apply($this->parserDataEntity, [
                'filter' => $this->ormEntity->getFilter()
            ])->getFilter();

            return empty($result) ? null : $result;
        }
        return null;
    }

    public function getQueryWhereOrs(): ?QueryWhereOrs
    {
        if ($result = $this->ormEntity->getWhereOr()) {
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
        if ($result = $this->ormEntity->getOrderBy()) {
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
}
