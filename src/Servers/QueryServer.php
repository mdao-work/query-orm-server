<?php


namespace mdao\QueryOrm\Servers;

use mdao\QueryOrm\Contracts\OrmEntityContract;
use mdao\QueryOrm\Contracts\QueryServerContract;
use mdao\QueryOrm\Entities\OrmEntity;
use mdao\QueryOrm\Entities\ParserDataEntity;
use mdao\QueryOrm\Entities\ParserEntity;
use mdao\QueryOrm\Entities\QueryFilter;
use mdao\QueryOrm\Entities\QueryOrderBy;
use mdao\QueryOrm\Entities\QueryPagination;
use mdao\QueryOrm\Entities\QuerySelect;
use mdao\QueryOrm\Exception\ParserException;
use mdao\QueryOrm\Parser;

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
     * @return array
     * @throws ParserException
     */
    public function getQueryFilter(): ?array
    {
        if ($result = $this->ormEntity->getFilter()) {
            return $this->parser->apply($this->parserDataEntity, [
                'filter' => $this->ormEntity->getFilter()
            ])->getFilter();
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
