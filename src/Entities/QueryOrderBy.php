<?php


namespace mdao\QueryOrm\Entities;

use mdao\QueryOrm\Contracts\Arrayable;

class QueryOrderBy implements Arrayable
{
    /**
     * desc,asc
     */
    const DIRECTION = [
        'asc', 'desc'
    ];

    /**
     * $field 排序字段
     * @var string|array
     */
    protected $column;

    /**
     * 排序
     * @var string
     */
    protected $direction;

    /**
     * QueryOrderBy constructor.
     * @param string $column 排序字段
     * @param string $direction 排序
     */
    public function __construct($column, string $direction)
    {
        $this->setColumn($column);
        $this->setDirection($direction);
    }

    /**
     * @return array|string
     */
    public function getColumn()
    {
        return $this->column;
    }

    /**
     * @param array|string $column
     */
    public function setColumn($column): void
    {
        $this->column = $column;
    }

    /**
     * @return string
     */
    public function getDirection(): string
    {
        return $this->direction;
    }

    /**
     * @param string $direction
     */
    public function setDirection(string $direction): void
    {
        if (!in_array($direction, self::DIRECTION)) {
            $direction = self::DIRECTION[0];
        }
        $this->direction = $direction;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'column' => $this->getColumn(),
            'direction' => $this->getDirection(),
        ];
    }
}
