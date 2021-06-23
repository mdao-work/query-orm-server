<?php


namespace mdao\QueryOrmServer;

use mdao\QueryOrmServer\Entities\QueryWhereExp;
use mdao\QueryOrmServer\Exception\ParserException;
use mdao\QueryOrmServer\Entities\ParserEntity;
use mdao\QueryOrmServer\Contracts\ParserEntityContract;

class Parser
{

    protected $filter = 'filter';
    protected $whereOr = 'where_or';
    protected $orderBy = 'order_by';
    protected $sortedBy = 'sorted_by';
    protected $page = 'page';
    protected $pageSize = 'page_size';
    protected $select = 'select';

    /**
     * @param ParserEntityContract $parserEntityContract
     * @param $param
     * @return ParserEntity
     * @throws ParserException
     */
    public function apply(ParserEntityContract $parserEntityContract, $param): ParserEntity
    {

        $params = $parserEntityContract->apply($param);

        $filter = $params[$this->filter] ?? [];

        $filter = $this->where($filter);

        $whereOr = $params[$this->whereOr] ?? [];
        $whereOr = $this->where($whereOr);

        $orderBy = $params[$this->orderBy] ?? 'id';
        $sortedBy = $params[$this->sortedBy] ?? 'desc';
        $order = [];
        if (!empty($orderBy)) {
            $order = $this->order($orderBy, $sortedBy);
        }

        $page = $params[$this->page] ?? 1;
        $pageSize = $params[$this->pageSize] ?? 15;

        $pagination = $this->pagination($page, $pageSize);

        $select = $params[$this->select] ?? '';
        $select = trim($select, ',');
        $select = $this->select($select);

        return new ParserEntity($filter, $select, $order, $pagination, $whereOr);
    }

    /**
     * 解析字段
     * @param string $value
     * @return array|string[]
     */
    public function select(string $value): array
    {
        if (!stripos($value, ',')) {
            return [$value];
        }

        if ($values = explode(',', $value)) {
            return $values;
        }

        return [];
    }

    /**
     * @param array $values
     * @return array
     */
    public function where(array $values): array
    {
        return (new QueryWhereExp())->where($values);
    }

    /**
     * &page=1&page_size=15
     * @param int $page
     * @param int $pageSize
     * @return array|int[]
     */
    public function pagination(int $page, int $pageSize): array
    {
        return [$page, $pageSize];
    }

    /**
     * @param string $orderBy 字段
     * @param string $sortedBy asc|desc
     * @return array|string[]
     * @throws ParserException
     */
    public function order(string $orderBy, string $sortedBy): array
    {
        //$orderBy=filed adn ($orderBy='asc' or $orderBy='desc')
        if (!stripos($orderBy, ',') && !stripos($sortedBy, ',')) {
            return [[$orderBy, $sortedBy]];
        }

        //$orderBy=filed1,filed1 adn ($orderBy='asc' or $orderBy='desc')
        if (stripos($orderBy, ',') && !stripos($sortedBy, ',')) {
            $orderBys = explode(',', $orderBy);

            $results = [];
            foreach ($orderBys as $orderBy) {
                $results[] = [[$orderBy, $sortedBy]];
            }
            return $results;
        }

        //$orderBy=filed1,filed1 and （ $orderBy='asc,desc'）
        if (stripos($orderBy, ',') && stripos($sortedBy, ',')) {
            $orderBys = explode(',', $orderBy);
            $sortedBys = explode(',', $sortedBy);

            if (count($orderBys) != count($sortedBys)) {
                throw new ParserException('orderBy count != sortedBy count');
            }

            $result = array_combine($orderBys, $sortedBys);

            $results = [];
            foreach ($result as $index => $item) {
                $results[] = [$index, $item];
            }
            return $results;
        }

        throw new ParserException('error');
    }
}
