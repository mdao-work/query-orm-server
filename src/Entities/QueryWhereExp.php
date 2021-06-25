<?php


namespace mdao\QueryOrmServer\Entities;

class QueryWhereExp
{
    /**
     * 表达式配置
     * @var string[]
     */
    protected $expUrlKeyMapConfig = [
        'in' => 'in',
        'like' => 'like',

        '=' => 'eq',
        '!=' => 'neq',
        '<>' => 'neq',
        '<' => 'lt',
        '<=' => 'elt',
        '>' => 'gt',
        '>=' => 'egt',

        'between' => 'between',
        'between time' => 'between_time',
        'not in' => 'not_in',
        'not between' => 'not_between',

    ];

    /**
     * url 表达式配置
     * @var string[]
     */
    protected $expUrlConfig = [
        'in' => 'in',
        'like' => 'like',

        'eq' => '=',
        'neq' => '<>',
        'lt' => '<',
        'elt' => '<=',
        'gt' => '>',
        'egt' => '>=',
        'between' => 'between',
        'not_in' => 'not in',
        'not_between' => 'not between',
        'between_time' => 'between time',
    ];

    /**
     * @SuppressWarnings(PHPMD)
     * @codingStandardsIgnoreStart
     * @param array $values
     * @return array
     */
    public function where(array $values): array
    {
        $where = [];
        $regexp = '/([a-zA-Z0-9_\.]+)(\{(?<operator>|eq|neq|gt|egt|lt|elt|like|in|between|not_in|not_between|between_time|\!?~)\})?/i';

        foreach ($values as $field => $value) {
            preg_match($regexp, "[{$field}]", $match);
            $operator = $match['operator'] ?? '';
            $operator = $this->expUrlConfig[$operator] ?? '=';
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

    /**
     * @param string $value
     * @return string|null
     */
    public function getExpUrlKeyMapValue(string $value): ?string
    {
        return $this->expUrlKeyMapConfig[$value] ?? 'eq';
    }

}
