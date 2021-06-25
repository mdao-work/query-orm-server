<?php


namespace mdao\QueryOrmServer\Entities;

use mdao\QueryOrmServer\Contracts\Arrayable;

class QueryWhere implements Arrayable
{
    /**
     * @var string
     */
    protected $field;
    /**
     * @var string
     */
    protected $operator;

    /**
     * @var array|int|string
     */
    protected $value;

    /**
     * QueryWhere constructor.
     * @param string $field
     * @param string $operator
     * @param string|array|int $value
     */
    public function __construct(string $field, string $operator, $value)
    {
        $this->setField($field);
        $this->setOperator($operator);
        $this->setValue($value);
    }

    /**
     * @return string
     */
    public function getField(): string
    {
        return $this->field;
    }

    /**
     * @param string $field
     */
    public function setField(string $field): void
    {
        $this->field = $field;
    }

    /**
     * @return string
     */
    public function getOperator(): string
    {
        return $this->operator;
    }

    /**
     * @param string $operator
     */
    public function setOperator(string $operator): void
    {
        $this->operator = $operator;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        if (is_array($this->value)) {
            return empty($this->value) ? '' : implode(',', $this->value);
        }
        return $this->value;
    }


    /**
     * @param array|int|string $value
     */
    public function setValue($value): void
    {
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function parserOperator(): string
    {
        $operator = (new QueryWhereExp())->getExpUrlKeyMapValue($this->getOperator());
        $field = $this->getField();
        //字段{表达式}
        return $field . "{" . $operator . "}";
    }

    /**
     * @return array
     */
    public function valueToArray(): array
    {
        return is_array($this->value) ? $this->value : explode(',', $this->value);
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            $this->getField(),
            $this->getOperator(),
            $this->getValue(),
        ];
    }
}
