<?php


namespace mdao\QueryOrmServer;

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
     *filed[~] like
     *filed[in] in
     *field[!] !=
     *field[=] =
     *field[>] >
     *field[>=] >=
     *field[<] <
     *field[<=] <=
     *field[<>] BETWEEN
     *field[><] NOT BETWEEN
     * @param array $values
     * @return array
     */
    public function where(array $values): array
    {
        $where = [];
        $regexp = '/([a-zA-Z0-9_\.]+)(\{(?<operator>|eq|neq|gt|egt|lt|elt|like|in|between|not_in|not_between|\!?~)\})?/i';

        foreach ($values as $field => $value) {
            preg_match($regexp, "[{$field}]", $match);
            $operator = $match['operator'] ?? '';
            $operator = $this->operator($operator);

            //是否字符串数组，是字符串数组，转换成数组
            $tempValue = $this->stringArrayConvertToArray($value);
            if (is_array($value) || !empty($tempValue)) {
                $operator = 'in';
                $value = empty($tempValue) ? $value : $tempValue;
            }

            $where[] = [$match[1], $operator, $value];
        }
        return $where;
    }

    /**
     * @param string $value
     * @return string
     */
    public function operator(string $value): string
    {
        switch ($value) {
            case "in":
                $operator = 'in';
                break;
            case "like":
                $operator = 'like';
                break;
            case "neq":
                $operator = '<>';
                break;
            case "lt":
                $operator = '<';
                break;
            case "elt":
                $operator = '<=';
                break;
            case "gt":
                $operator = '>';
                break;
            case "egt":
                $operator = '>=';
                break;
            case "between":
                $operator = 'between';
                break;
            case "not_between":
                $operator = 'not between';
                break;
            case "eq":
            default:
                $operator = '=';
                break;
        }
        return $operator;
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

    /**
     * 字符串、数组转换为格式化的数组
     * @param string $data 原始字符串
     * @return array
     */
    private function stringArrayConvertToArray(string $data): array
    {
        // 数组原样返回
        if (is_array($data)) {
            return $data;
        }
        $result = [];
        // 字符串处理
        $string = (string)$data;
        if (!empty($string) && preg_match('/^\[.*?]$/', $string)) {
            $result = json_decode($string, true);
        }
        if (!is_array($result) || count($result) < 1) {
            return [];
        }
        return $result;
    }
}
