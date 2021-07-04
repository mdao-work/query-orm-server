<?php


namespace mdao\QueryOrmServer;

use mdao\QueryOrmServer\Entities\QueryWhereExp;
use mdao\QueryOrmServer\Exception\ParserException;

class Parser
{
    /**
     * @param array $params
     * @param Config $config
     * @return array
     * @throws ParserException
     */
    public function apply(array $params, Config $config): array
    {
        $filter = $params[$config->getFilter()] ?? null;

        if (!is_null($filter) && $filter != '') {
            $filter = $this->where($filter);
        }

        $whereOr = $params[$config->getWhereOr()] ?? null;

        if (!is_null($whereOr) && $whereOr != '') {
            $whereOr = $this->where($whereOr);
        }

        $orderBy = $params[$config->getOrderBy()] ?? null;
        $sortedBy = $params[$config->getSortedBy()] ?? null;
        $order = null;
        if (!is_null($orderBy) && !is_null($sortedBy) && $orderBy != '' && $sortedBy != '') {
            $order = $this->order($orderBy, $sortedBy);
        }

        $page = $params[$config->getPage()] ?? null;
        $pageSize = $params[$config->getPageSize()] ?? null;
        $pagination = null;
        if (!is_null($page) && !is_null($pageSize) && $page != '' && $pageSize != '') {
            $pagination = $this->pagination($page, $pageSize);
        }

        $select = $params[$config->getSelect()] ?? null;
        if (!is_null($select) && $select != '') {
            $select = trim($select, ',');
            $select = $this->select($select);
        }

        return [
            'where' => $filter,
            'where_or' => $whereOr,
            'order' => $order,
            'pagination' => $pagination,
            'select' => $select
        ];
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
